<?php
namespace App\Livewire\Workshop\Screen;

use App\Livewire\Workshop\HasCodeMirror;
use App\Models\Control;
use App\Models\Enums\Command;
use App\Models\Enums\ControlType;
use App\Models\Scenario;
use App\Models\Services\StoreService;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Controls extends Component
{
    use HasCodeMirror;

    #[Locked]
    public int $screenId;
    #[Locked]
    public array $scenarios;
    #[Locked]
    public array $controls = [];
    #[Locked]
    public string $action;
    #[Locked]
    public ?int $controlId = null;
    public array $state;
    public bool $scenarioLogic = false;
    public bool $addLogic = false;

    public function mount(int $screenId): void
    {
        $this->screenId = $screenId;
        $this->scenarios = Scenario::where('user_id', auth()->id())
            ->select(['id', 'title as name'])->get()->toArray();
        /** @var Collection<int, Control> $controls */
        $controls = Control::where('screen_id', $screenId)->with('scenario')->get();
        foreach ($controls as $control) {
            $this->controls[$control->id] = $this->prepareControl($control);
        }
        $this->resetForm();
    }

    public function render(): mixed
    {
        return view('livewire.workshop.screen.controls');
    }

    public function updatedScenarioLogic(): void
    {
        if ($this->scenarioLogic) {
            $this->state['command'] = null;
        } else {
            $this->state['scenario_id'] = null;
        }
    }

    public function updatedAddLogic(): void
    {
        if (!$this->addLogic) {
            $this->state['command'] = null;
            $this->state['scenario_id'] = null;
            $this->scenarioLogic = false;
        }
    }

    protected function codeMirrorFields(): array
    {
        return ['beforeString', 'afterString'];
    }

    public function resetForm(): void
    {
        $this->action = 'create';
        $this->controlId = null;
        foreach (array_keys($this->rules()) as $field) {
            $this->state[$field] = null;
        }
        $this->addLogic = false;
        $this->scenarioLogic = false;
        $this->dispatchCodeMirror();
    }

    public function edit(int $id): void
    {
        $this->controlId = $id;
        $this->action = 'edit';
        $control = $this->controls[$id];
        foreach (array_keys($this->rules()) as $field) {
            $this->state[$field] = $control[$field];
        }
        $this->addLogic = !empty($control['scenario_id']) || !empty($control['command']);
        $this->scenarioLogic = !empty($control['scenario_id']);
        $this->dispatchCodeMirror();
        Flux::modal('form-control')->show();
    }

    public function submit(): void
    {
        $data = $this->validate(\Arr::prependKeysWith($this->rules(), 'state.'));
        $control = $this->getModel();
        /** @var Control $control */
        $control = StoreService::handle($data['state'], $control);
        $this->controls[$control->id] = $this->prepareControl($control);
        $this->resetForm();
        Flux::modal('form-control')->close();
        $this->dispatch('flash', message: 'Control' . ($this->controlId ? ' updated' : ' created'));
    }

    public function delete(int $id): void
    {
        $this->controlId = $id;
        $control = $this->getModel();
        $control->delete();
        unset($this->controls[$id]);
        $this->dispatch('flash', message: 'Control deleted');
        $this->resetForm();
    }

    protected function getModel(): Control
    {
        return $this->controlId ?
            Control::findOrFail($this->controlId) :
            new Control(['screen_id' => $this->screenId]);
    }

    protected function prepareControl(Control $control): array
    {
        return [
            'scenario_id' => $control->scenario_id,
            'scenarioTitle' => $control->scenario?->title,
            'command' => $control->command?->value,
            'commandTitle' => $control->command ? ucfirst($control->command->value) : null,
            'type' => $control->type->value,
            'title' => $control->title,
            'tooltip' => $control->tooltip,
            'description' => !empty($control->description) ?
                $control->description :
                $control->scenario?->description,
            'beforeString' => $control->beforeString,
            'afterString' => $control->afterString,
        ];
    }

    protected function rules(): array
    {
        $dlsEditor = config('dsl.editor');
        $rules = [
            'type'          => ['required', 'string', Rule::enum(ControlType::class)],
            'title'         => ['string', 'required'],
            'tooltip'       => ['nullable', 'string'],
            'description'   => ['nullable', 'string'],
            'beforeString'  => ['nullable', $dlsEditor],
            'afterString'   => ['nullable', $dlsEditor]
        ];
        $required = $this->addLogic ? 'required' : 'nullable';

        return array_merge($rules, $this->scenarioLogic ? [
            'scenario_id'   => [$required, 'int'],
            'command'       => ['nullable', 'string'],
        ] : [
            'command'       => [$required, 'string', Rule::enum(Command::class)],
            'scenario_id'   => ['nullable', 'int']
        ]);
    }
}
