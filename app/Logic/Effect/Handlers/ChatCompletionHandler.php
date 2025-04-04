<?php

namespace App\Logic\Effect\Handlers;

use App\Logic\Contracts\EffectHandlerContract;
use App\Logic\Dsl\ValueResolver;
use App\Logic\Facades\EffectRunner;
use App\Logic\Process;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Executes the `chat.completion` effect by sending a chat request to OpenAI
 * and optionally handling tool calls and regular message responses.
 * Resulting `content`, `calls.*`, and `call.arguments` are injected into the process
 * for further use across subsequent logic blocks.
 *
 * Context:
 * - Used by the DSL effect `chat.completion`.
 * - Depends on configuration provided via resolved DSL params (model, messages, etc).
 * - Executes downstream effects (content, calls.*) if provided.
 * - Compiles only request-relevant fields initially (model, messages, etc).
 * - Defers execution of content/calls blocks until OpenAI response is available.
 * - Stores tool call results as stdClass to support Symfony expression language access.
 */
class ChatCompletionHandler implements EffectHandlerContract
{
    public function __construct(protected array $params) {}

    public function describeLog(Process $process): ?string
    {
        $model = $this->params['model'] ?? 'unknown';
        $messages = $this->params['messages'] ?? null;
        $tools = $this->params['tools'] ?? null;

        $summary = "OpenAI chat completion with model: {$model}";

        if (is_array($messages)) {
            $summary .= ", messages: " . count($messages);
        }

        if (!empty($tools)) {
            $summary .= ", tools: " . implode(', ', array_keys($tools));
        }

        return $summary;
    }

    /**
     * Sends chat completion request to OpenAI and handles response:
     * - sets `content` if present;
     * - stores `calls.*` if tool calls are returned;
     * - invokes downstream effects if configured.
     *
     * @throws Throwable on API or execution failure
     */
    public function execute(Process $process): void
    {
        $compiled = ValueResolver::resolve(Arr::except($this->params, ['calls', 'content']), $process);
        $request = $this->buildRequest($compiled);

        try {
            // Cleanup previously injected response data (if re-entered)
            $process->forget(['call', 'content', 'calls']);

            $response = OpenAI::chat()->create($request);
            $choice = $response->choices[0]->message;

            // Handle regular content-based response
            if (!empty($choice->content)) {
                $process->set('content', $choice->content);
                $effects = $this->params['content'] ?? null;
                if ($effects) {
                    $compiled = ValueResolver::resolve($effects, $process);
                    EffectRunner::run($compiled, $process);
                }
            }

            // Handle tool function calls (if any)
            if (isset($choice->toolCalls)) {
                foreach ($choice->toolCalls as $toolCall) {
                    $call = new \stdClass();
                    $call->name = $toolCall->function->name;
                    $call->arguments = json_decode($toolCall->function->arguments ?? '{}', false);
                    $process->push('calls', $call);
                }
                $this->handleCalls($process);
            }
        } catch (Throwable $e) {
            $this->notifyError($e, $process, $request);
            throw $e;
        }
    }

    /**
     * Prepares OpenAI API payload by extracting allowed fields
     * and formatting optional tools block (if defined).
     */
    protected function buildRequest(array $config): array
    {
        return Arr::only($config, [
                'model',
                'messages',
                'temperature',
                'max_tokens',
                'top_p',
                'stop',
                'presence_penalty',
                'frequency_penalty',
                'response_format',
                'tool_choice',
            ]) + $this->prepareTools($config);
    }

    /**
     * Transforms tool map (name => schema) into OpenAI-compatible tool list.
     */
    protected function prepareTools(array $config): array
    {
        if (empty($config['tools']) || !is_array($config['tools'])) {
            return [];
        }

        $tools = [];
        foreach ($config['tools'] as $name => $tool) {
            $tools[] = [
                'type' => 'function',
                'function' => [
                    'name' => $name,
                    'description' => $tool['description'] ?? '',
                    'parameters' => $tool['parameters'] ?? [],
                ],
            ];
        }

        return ['tools' => $tools];
    }

    /**
     * Resolves and executes handlers for all received tool calls.
     * Each tool handler receives `call` arguments in the process.
     */
    protected function handleCalls(Process $process): void
    {
        if (empty($this->params['calls'])) {
            $this->notifyMissedHandler($this->params, $process);
        }
        $compiled = ValueResolver::resolve($this->params['calls'], $process);
        foreach ($process->get('calls', []) as $call) {
            $block = $compiled[$call->name] ?? null;
            if ($block) {
                $process->set('call', $call->arguments);
                EffectRunner::run($block, $process);
            } else {
                $this->notifyMissedHandler($call, $process);
            }
        }
    }

    /**
     * Called when no handler is defined for a tool call.
     * For now, logs the issue — future version should notify author.
     */
    protected function notifyMissedHandler(array $call, Process $process): void
    {
        Log::warning("No handler defined for tool in chat.completion effect.", [
            'call' => $call,
            'process' => $process->pack()
        ]);
    }

    /**
     * Logs (and later: notifies) runtime or API errors.
     */
    protected function notifyError(Throwable $e, Process $process, array $request): void
    {
        Log::error('[Effect][chat.completion] Error', [
            'error' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ],
            'request' => $request,
            'params' => $this->params,
            'process' => $process->pack()
        ]);
    }
}
