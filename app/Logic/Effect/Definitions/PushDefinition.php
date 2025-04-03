<?php

namespace App\Logic\Effect\Definitions;

use App\Logic\Contracts\EffectDefinitionContract;

class PushDefinition implements EffectDefinitionContract
{
    public const KEY = 'push';

    public static function key(): string
    {
        return self::KEY;
    }

    public static function describe(): array
    {
        return [
            'title' => 'Push to Array',
            'description' => 'Adds a value to the end of a list inside the process container. Target must be an indexed array.',
            'fields' => [
                '*' => [
                    'type' => 'expression',
                    'description' => 'Value to append. Must resolve to a single item.',
                ],
            ],
            'examples' => [
                [
                    'push' => [
                        'steps' => '>>start',
                        'events.log' => [
                            'type' => '>>info',
                            'message' => '>>Ready',
                        ],
                        'messages' => 'message_entry'
                    ]
                ]
            ],
        ];
    }

    public static function rules(): array
    {
        return [
            '*' => 'required',
        ];
    }

    public static function nestedEffects(array $params): array
    {
        return [];
    }
}
