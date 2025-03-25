<?php

namespace App\Livewire\Chat;

use App\Livewire\PresenceTrait;
use App\Models\Application;
use App\Models\Chat;
use App\Models\Control;
use App\Models\Enums\ChatStatus;
use App\Models\Enums\ControlType;
use App\Models\Member;
use App\Models\Memory;
use App\Models\Screen;
use App\Models\Transfer;
use App\Notifications\ChatPlaying;
use App\Notifications\ScreenUpdated;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.play')]
class Play extends Component
{
    use PresenceTrait;

    #[Locked]
    public Chat $chat;
    #[Locked]
    public int $memberId;
    #[Locked]
    public Application $application;
    #[Locked]
    public Screen $screen;
    #[Locked]
    public array $screenHistory = [];
    #[Locked]
    public array $transfers;
    #[Locked]
    public array $actions;
    #[Locked]
    public array $inputs;
    #[Locked]
    public ?array $activeInput;
    #[Locked]
    public array $memories;

    public string $message = '';
    #[Locked]
    public Collection $onlineMembers;
    #[Locked]
    public Collection $offlineMembers;

    protected function getListeners(): array
    {
        return [
            'usersHere' => 'here',
            'userJoining' => 'joining',
            'userLeaving' => 'leaving',
        ];
    }

    #[On('refresh.screen')]
    public function refresh(): void
    {
        $this->chat->load('memories.member');
        $this->prepareMemories();
    }

    public function mount(int $id): void
    {
        $this->chat = Chat::with([
            'application.screens.controls.scenario',
            'application.screens.transfers',
            'members.mask',
            'memories.member'
        ])->findOrFail($id);
        if (!$this->canPlay()) {
            $this->redirectRoute('chats.view', ['id' => $id], true, true);
        }
        $this->application = $this->chat->application;
        $this->memberId = $this->chat->takenSeats->where('user_id', auth()->id())->first()->id;
        $this->initMembers();
        $this->initScreen($this->application->initScreen);
    }

    protected function initMembers(): void
    {
        $this->offlineMembers = $this->chat->takenSeats->filter(
            fn($member) => !in_array($member->user_id, $this->userIds)
        );
        $this->onlineMembers = $this->chat->takenSeats->filter(
            fn($member) => in_array($member->user_id, $this->userIds)
        );
    }

    protected function initScreen(Screen $screen, bool $withHistory = true): void
    {
        if ($withHistory) {
            $this->screenHistory[] = $screen->id;
        }
        $this->screen = $screen;
        $this->prepareControl();
        $this->prepareMemories();
    }

    protected function prepareControl(): void
    {
        $this->transfers = collect($this->getTransfers())->keyBy('id')->toArray();
        $this->actions = collect($this->getControls(ControlType::Action->value))->keyBy('id')->toArray();
        $this->inputs = collect($this->getControls(ControlType::Input->value))->keyBy('id')->toArray();
        $this->activeInput = reset($this->inputs) ?: null;
    }

    protected function prepareMemories(): void
    {
        $this->memories =
            collect($this->getMemories($this->screen->code))->keyBy('id')->toArray();
    }

    protected function getTransfers(): array
    {
        return $this->screen->transfers->map(fn(Transfer $transfer) => [
            'id' => $transfer->screen_to_id,
            'title' => $transfer->title,
            'tooltip' => $transfer->tooltip,
            'before' => $transfer->before,
            'after' => $transfer->after,
        ])->toArray();
    }

    protected function getScreen(int $id): Screen
    {
        return $this->application->screens->findOrFail($id);
    }

    protected function getControls(string $type): array
    {
        return $this->screen->controls->where('type', $type)
            ->map(fn(Control $control) => [
                'id' => $control->id,
                'title' => $control->title,
                'tooltip' => $control->tooltip,
            ])->toArray();
    }

    protected function getControl(int $id): Control
    {
        return $this->screen->controls->findOrFail($id);
    }

    protected function getMemories(string $type): array
    {
        return $this->chat->memories->where('type', $type)
            ->map(fn(Memory $memory) => [
                'id' => $memory->id,
                'member_id' => $memory->member_id,
                'user_id' => $memory->member?->user_id,
                'mask_name' => $memory->member?->maskName,
                'image_id' => $memory->image_id,
                'title' => $memory->title,
                'content' => $memory->content,
                'type' => $memory->type,
                'meta' => $memory->meta,
                'created_at' => $memory->created_at
            ])->toArray();
    }

    public function transfer(int $screenId): void
    {
        $screen = $this->getScreen($screenId);
        // todo - check screen active condition
        $this->initScreen($screen);
    }

    public function action(int $controlId): void
    {
        $control = $this->getControl($controlId);

        // todo - process control - command or scenario
        if ($control->command?->value === 'back') {
            array_pop($this->screenHistory);
            if (!$screenId = end($this->screenHistory)) {
                return; // todo
            }
            $this->initScreen($this->getScreen($screenId), false);
            return;
        }
        // todo - check screen active condition
        $content = $this->getMember()->maskName . ' used action ' . $control->title;
        $this->createMemory($this->screen->code, $content);
    }

    /**
     * @throws \Exception
     */
    public function changeInput(int $controlId): void
    {
        if (!isset($this->inputs[$controlId])) {
            throw new \Exception('Input not found: ' . $controlId);
        }

        $this->activeInput = $this->inputs[$controlId];
    }

    public function render(): mixed
    {
        return view('livewire.chat.play')
            ->title('Chat Play: ' . $this->chat->title);
    }

    protected function handleHere(): void
    {
        $this->initMembers();

        $message = $this->getMember()->maskName . ' is playing "' . $this->chat->title . '"';
        $link = route('chats.play', ['id' => $this->chat->id]);
        /** @var Member $member */
        foreach ($this->offlineMembers as $member) {
            $member->user->notifyNow(new ChatPlaying($message, $link));
        }
    }

    protected function handleJoining(int $id): void
    {
        $this->initMembers();
    }

    protected function handleLeaving(int $id): void
    {
        $this->initMembers();
    }

    public function close(): void
    {
        $this->redirectRoute('chats.view', ['id' => $this->chat->id], true, true);
    }

    public function sendMessage(): void
    {
        // todo - run active input - command or scenario
        // todo - test messages:
        $this->createMemory($this->screen->code, $this->message, $this->memberId);
        $this->message = '';
    }

    protected function createMemory(string $type, string $content, ?int $memberId = null): void
    {
        Memory::create([
            'chat_id' => $this->chat->id,
            'member_id' => $memberId,
            'content' => $content,
            'type' => $type
        ]);
        /** @var Member $member */
        foreach ($this->onlineMembers as $member) {
            if ($member->id !== $this->memberId) {
                $member->user->notifyNow(new ScreenUpdated());
            }
        }

        $this->refresh();
    }

    protected function canPlay(): bool
    {
        return $this->chat->status === ChatStatus::Started &&
            !!$this->chat->members->where('user_id', auth()->id())
                ->where('is_confirmed', true)->count();
    }

    protected function getMember(?int $memberId = null): Member
    {
        $memberId = $memberId ?: $this->memberId;
        return $this->chat->takenSeats->where('id', $memberId)->firstOrFail();
    }
}
