<div>

    {{-- Modal Form --}}
    <div class="flex justify-end mb-2">
        <flux:modal.trigger name="form-control">
            <flux:button icon="plus-circle" variant="primary" class="cursor-pointer"/>
        </flux:modal.trigger>
    </div>
    <flux:modal name="form-control"
                x-on:cancel="$wire.resetForm()"
                x-on:close="$wire.resetForm()" class="!max-w-4xl min-w-xl">
        <div class="space-y-4">
            <flux:heading>{{ $this->controlId ? __('Edit Control') : __('Create Control') }}</flux:heading>

            <flux:field class="mb-3">
                <flux:label>Type</flux:label>
                <flux:select wire:model="state.type" class="cursor-pointer">
                    <flux:select.option selected>Not selected</flux:select.option>
                    @foreach (\App\Models\Enums\ControlType::options() as $value => $title)
                        <flux:select.option value="{{ $value }}">
                            {{ $title }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="state.type"/>
            </flux:field>

            <flux:field class="mb-3">
                <flux:label>Title</flux:label>
                <flux:input type="text" wire:model="state.title"/>
                <flux:error name="state.title"/>
            </flux:field>
            <flux:field class="mb-3">
                <flux:label>Tooltip</flux:label>
                <flux:textarea wire:model="state.tooltip" rows="auto"></flux:textarea>
                <flux:error name="state.tooltip"/>
            </flux:field>
            <flux:field class="mb-3">
                <flux:label>Description</flux:label>
                <flux:textarea wire:model="state.description" rows="auto"></flux:textarea>
                <flux:error name="state.description"/>
            </flux:field>

            {{-- Effects --}}
            <flux:field class="mb-3">
                <flux:label>Effects ({{ config('dsl.editor', 'yaml') }})</flux:label>
                <x-code-mirror wire:key="{{ $codeMirrorPrefix }}.beforeString"
                               :lang="config('dsl.editor', 'yaml')"
                               :content="$state['beforeString'] ?? ''"
                               wire:model.defer="state.beforeString" class="w-full" />
                <flux:error name="state.beforeString"/>
            </flux:field>

            {{-- Add Logic --}}
            <flux:field>
                <div class="flex gap-2">
                    <flux:switch label="Add Logic" class="cursor-pointer" wire:model.live="addLogic"/>
                </div>
            </flux:field>
            @if($addLogic)
                <flux:field class="mb-3">
                    <x-searchable-select field="scenario_id" :options="$scenarios"/>
                    <flux:error name="state.scenario_id"/>
                </flux:field>
                {{-- Effects After --}}
                <flux:field class="mb-3">
                    <flux:label>Effects After ({{ config('dsl.editor', 'yaml') }})</flux:label>
                    <x-code-mirror wire:key="{{ $codeMirrorPrefix }}.afterString"
                                   :lang="config('dsl.editor', 'yaml')"
                                   :content="$state['beforeString'] ?? ''"
                                   wire:model.defer="state.afterString" class="w-full" />
                    <flux:error name="state.afterString"/>
                </flux:field>
            @endif

            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">
                        {{ __('Close') }}
                    </flux:button>
                </flux:modal.close>
                <flux:button wire:click="submit" variant="primary" class="cursor-pointer">
                    {{ __('Submit') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Controls List --}}
    <div class="space-y-2">
        @if($controls)
            <div
                class="grid grid-cols-5 gap-4 font-bold text-sm text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md px-4 py-2">
                <span>Type</span>
                <span>Title</span>
                <span>Tooltip</span>
                <span>Logic</span>
                <span class="text-right">Actions</span>
            </div>
        @endif

        @foreach($controls as $id => $control)
            @php
                $hasScenario = !empty($control['scenario_id']);
                $logicTitle = $hasScenario ? $control['scenarioTitle'] : 'None';
            @endphp

            <div x-data="{ open: false }"
                 class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow transition-all duration-300">
                {{-- Row --}}
                <div class="grid grid-cols-5 gap-4 items-center px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">
                    {{ ucfirst($control['type']) }}
                </span>
                    <span class="text-sm text-zinc-600 dark:text-zinc-300">
                    {{ $control['title'] }}
                </span>
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $control['tooltip'] }}
                </span>
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                        @if(!empty($hasScenario))
                            <a class="underline" wire:click.stop wire:navigate
                               href="{{ route('workshop.scenarios', ['action' => 'view', 'id' => $control['scenario_id']]) }}">
                            {{ $logicTitle }}
                        </a>
                        @else
                            {{ $logicTitle }}
                        @endif
                    </span>

                    {{-- Expand toggle + actions --}}
                    <div class="flex justify-end gap-2">
                        <flux:button x-show="open" size="sm" icon="chevron-up"
                                     @click="open = !open" class="cursor-pointer"/>
                        <flux:button x-show="!open" size="sm" icon="chevron-down"
                                     @click="open = !open" class="cursor-pointer"/>
                        <flux:button size="sm" icon="pencil-square" wire:click.stop="edit({{ $id }})"
                                     variant="primary" class="cursor-pointer"/>
                        <flux:button size="sm" icon="trash" wire:click.stop="delete({{ $id }})"
                                     variant="danger" class="cursor-pointer"/>
                    </div>
                </div>

                {{-- Expandable section --}}
                <div x-show="open" x-transition class="px-6 pb-4 pt-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400">Description</label>
                        <pre class="bg-zinc-100 dark:bg-zinc-800 p-2 rounded text-xs overflow-auto whitespace-pre-wrap">
                            {!! e($control['description']) !!}
                        </pre>
                    </div>
                    <x-effects-view :before-string="$control['beforeString']" :after-string="$control['afterString']"/>
                </div>
            </div>
        @endforeach
    </div>

</div>
