<style>
    .table-cell-content {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
        overflow: hidden;
        text-overflow: ellipsis;
        max-height: 9rem;
        line-height: 1.5rem;
    }
</style>

<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
    <thead class="sticky top-0 z-10 bg-zinc-100 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
    <tr>
        <th class="px-4 py-4 w-20 text-left font-bold uppercase tracking-wider">
            ID
        </th>
        @foreach($fields as $field => $title)
            <th class="px-4 py-4 text-left font-bold uppercase tracking-wider">{{ $title }}</th>
        @endforeach
        <th class="px-4 py-4 w-20 text-left font-bold uppercase tracking-wider">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @foreach($models as $id => $data)
        <tr>
            <td class="px-4 py-2 whitespace-nowrap">{{ $id }}</td>
            @foreach($fields as $field => $title)
                <td class="px-4 py-2">
                    <div class="table-cell-content" data-field="{{ $field }}">
                    @switch($this->type($field))
                        @case('image')
                        <div class="max-w-40">
                            <x-image-viewer path="{{ $data[$field] }}" alt="{{ $field }}"/>
                        </div>
                        @break
                        @case('template')
                            @if($this->config($field, 'callback'))
                                <div class="px-4 py-2 whitespace-normal text-gray-900 dark:text-gray-300">
                                    {!! nl2br($state[$field]) !!}
                                </div>
                            @else
                                @php
                                    $params = $this->templateParams('index', $field);
                                    if(is_callable($params)) {
                                        $params = $params($this->getModel($id));
                                    }
                                @endphp
                                @include($this->config($field, 'template'), $params)
                            @endif
                            @break
                        @default
                        {!! $data[$field] !!}
                        @break
                    @endswitch
                    </div>
                </td>
            @endforeach
            <td class="px-4 py-2 whitespace-nowrap">
                <flux:button.group>
                @foreach($actions as $actionName => $actionInfo)
                    @if(in_array($id, $actionInfo['ids']))
                        <flux:tooltip :content="\App\Crud\AbstractCrud::title($actionName)">
                            <flux:button :icon="$actionInfo['icon']" class="cursor-pointer"
                                         wire:click="{{ $actionName }}({{ $id }})"></flux:button>
                        </flux:tooltip>
                    @endif
                @endforeach
                </flux:button.group>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
