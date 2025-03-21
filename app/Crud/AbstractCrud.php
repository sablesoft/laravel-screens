<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedClassInspection */

namespace App\Crud;

use App\Crud\Traits\HandleForm;
use App\Crud\Traits\HandleIndex;
use App\Crud\Traits\HandlePaginate;
use App\Crud\Traits\HandleURI;
use App\Models\Services\StoreService;
use Flux\Flux;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class AbstractCrud extends Component
{
    use HandlePaginate, HandleForm, HandleIndex, HandleURI;

    public array $state = [];
    #[Locked]
    public ?int $modelId = null;
    #[Locked]
    public string $resourceTitle;
    #[Locked]
    public string $action = 'index';
    #[Locked]
    public int $userId;

    public ?int $deleteId = null;

    abstract public function className(): string;

    abstract protected function fieldsConfig(): array;

    public function mount(?string $action = 'index', ?int $id = null): void
    {
        $this->userId = auth()->id();
        $this->resourceTitle = $this->classTitle();
        if ($id) {
            $this->modelId = $id;
            // check is owner:
            $model = $this->getResource();
            if ($model->user_id !== $this->userId) {
                $this->modelId = null;
                $this->dispatch('flash', message: 'You cannot do it!');
                $this->changeUri();
            } else {
                $action = in_array($action, ['view', 'edit']) ? $action : 'view';
                $this->$action($id);
            }
        } elseif ($action === 'create') {
            $this->create();
        } else {
            $this->changeUri();
        }
    }

    protected function getListeners(): array
    {
        return [
             'refresh.'. $this->getType() => '$refresh',
        ];
    }

    public function components(string $action): array
    {
        return [];
    }

    public function componentParams(string $action, ?string $field = null): array
    {
        return [];
    }

    public function templateParams(string $action, ?string $field = null): array|callable
    {
        return [];
    }

    public function optionsParam(string $field, string $class): array
    {
        return [
            'field' => $field,
            'options' => $this->filterByOwner($this->getQuery($class))
                ->select(['id', 'title as name'])->get()->toArray()
        ];
    }

    public function selectedOptionTitle(string $field, string $key): string
    {
        $options = $this->selectOptions($field);
        return $options[$key];
    }

    public function render()
    {
        $params = [];
        $this->checkedModels = null;
        if ($this->showForm) {
            $view = 'crud.form';
        } elseif ($this->action === 'index') {
            $view = 'crud.index';
            $params = [
                'models' => $this->paginator()
            ];
        } elseif ($this->action === 'view') {
            $view = 'crud.view';
        } else {
            throw new \Exception('Unknown crud view');
        }

        $title = config('app.name') . ' - ' . $this->classTitle();

        return view($view, $params)->title($title);
    }

    public function create(): void
    {
        $this->action = 'create';
        $this->resetState();
        foreach($this->fields('create') as $field) {
            $init = $this->config($field, 'init');
            if ($init) {
                $this->state[$field] = $this->$init();
            }
        }
        $this->openForm();
        $this->changeUri('create');
    }

    public function edit(int $id): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $model = $this->getModel($id);
        $this->modelId = $id;
        $this->resetState();
        foreach($this->fields('edit') as $field) {
            if ($this->type($field) === 'image') {
                $callback = $this->config($field, 'callback');
                $this->state[$field] = is_callable($callback) ?
                    $callback($model) : $model->$field;
            } else {
                if ($model->$field instanceof \UnitEnum) {
                    $enum = $model->$field;
                    $this->state[$field] = $enum->value;
                } else {
                    $this->state[$field] = $model->$field;
                }
            }
        }
        $this->action = 'edit';
        $this->openForm();
        $this->changeUri('edit', $id);
    }

    public function store(): void
    {
        $rules = $this->actionConfig($this->action, 'rules');
        if ($rules) {
            $data = $this->validate(\Arr::prependKeysWith($rules, 'state.'));
        }  else {
            return;
        }
        try {
            $model = $this->getResource();
            StoreService::handle($data['state'], $model);
            $this->dispatch('flash', message: $this->classTitle(false) . ($this->modelId ? ' updated' : ' created'));
            $this->close();
        } catch (\Throwable $e) {
            $this->dispatch('flash', message: config('app.debug') ? $e->getMessage() : 'Failed. Something wrong.');
            Log::error($e->getMessage(), ['exception' => $e]);
        }
    }

    public function close(): void
    {
        $this->formAction = 'store';
        $this->showForm = false;
        $this->modelId = null;
        $this->action = 'index';
        $this->resetState();
        $this->changeUri();
    }

    public function view(?int $id = null): void
    {
        $id = $id ?: $this->modelId;
        if (!$id) {
            return;
        }
        $model = $this->getModel($id);
        $this->close();
        $this->modelId = $id;
        foreach($this->fields('view') as $field) {
            $this->state[$field] = $this->getValue($model, $field);
        }
        $this->action = 'view';
        $this->changeUri('view', $id);
    }

    public function delete(int $id): void
    {
        $this->deleteId = $id;
        Flux::modal('delete-confirmation')->show();
    }

    public function deleteConfirmed(): void
    {
        $this->close();
        $this->resetCursor();
        $this->getModel($this->deleteId)?->delete();
        $this->deleteId = null;
        Flux::modal('delete-confirmation')->close();
        $this->dispatch('flash', message: $this->classTitle(false) . ' deleted');
    }

    public function classTitle(bool $plural = true): string
    {
        $parts = explode('\\', $this->className());
        return self::title(end($parts), $plural);
    }

    public function getType(): string
    {
        $parts = explode('\\', $this->className());
        return self::code(end($parts));
    }

    protected function actionConfig(string $action, ?string $option = null): array
    {
        $config = [];
        $all = $this->fieldsConfig();
        if ($action === 'all') {
            $config = $all;
        } else {
            foreach ($all as $field => $fieldConfig) {
                if (!isset($fieldConfig['action']) || in_array($action, (array) $fieldConfig['action'])) {
                    $config[$field] = $fieldConfig;
                }
            }
        }
        if (!$option) {
            return $config;
        }

        $prepared = [];
        foreach ($config as $field => $options) {
            if (!empty($options[$option])) {
                $prepared[$field] = $options[$option];
            }
        }

        return $prepared;
    }

    protected function fields(string $action): array
    {
        return array_keys($this->actionConfig($action));
    }

    protected function getValue(Model $model, string $field): ?string
    {
        $config = $this->fieldsConfig()[$field];
        if (!empty($config['callback'])) {
            $callback = $config['callback'];
            if (is_callable($callback)) {
                return $callback($model);
            }

            return $this->$callback($model);
        }

        return $model->$field;
    }

    protected function getModel(?int $id = null): ?Model
    {
        if (!is_null($this->models)) {
            $collection = collect($this->models->items());
            return $collection->where('id', $id)->first();
        }
        $className = $this->className();
        return $id ? $className::findOrFail($id) : new $className();
    }

    protected function resetState(): void
    {
        foreach($this->fields('all') as $field) {
            $this->state[$field] = null;
        }
    }

    public static function code(string $name, bool $plural = false): string
    {
        return Str::of($name)->kebab()->plural($plural ? 2 : 1);
    }

    public static function title(string $name, bool $plural = false): string
    {
        return Str::of(self::code($name, $plural))->replace('-', ' ')->title();
    }
}
