<?php

namespace App\Livewire\Workshop;

use App\Crud\AbstractCrud;
use App\Crud\Interfaces\ShouldHasMany;
use App\Crud\Traits\HandleHasMany;
use App\Crud\Traits\HandleImage;
use Illuminate\Database\Eloquent\Builder;

class Application extends AbstractCrud implements ShouldHasMany
{
    use HandleHasMany, HandleImage;

    public function className(): string
    {
        return \App\Models\Application::class;
    }

    public function orderByFields(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title'
        ];
    }

    public function templateParams(string $action, ?string $field = null): array
    {
        if (array_key_exists($field, $this->getHasManyFields())) {
            $class = $this->getHasManyFields()[$field];
            return $this->optionsParam($field, $class);
        }

        return match ($field) {
            'screen_id' => $this->optionsParam($field, \App\Models\Screen::class),
            'image_id' => $this->imageParam(),
            default => [],
        };
    }

    protected function fieldsConfig(): array
    {
        return [
            'image' => $this->getThumbnailField(),
            'title' => [
                'action' => ['index', 'create', 'edit', 'view'],
                'rules' => 'required|string',
            ],
            'image_id' => $this->imageField(),
            'description' => [
                'action' => ['index', 'create', 'edit', 'view'],
                'type' => 'textarea',
                'rules' => 'nullable|string'
            ],
            'constants' => [
                'action' => ['edit', 'view'],
                'type' => 'textarea',
                'rules' => 'nullable|json'
            ],
            'screens' => $this->hasManyField('screens', ['view']),
            'is_public' => [
                'title' => 'Public',
                'action' => ['index', 'edit', 'view'],
                'type' => 'checkbox',
                'rules' => [
                    'bool',
                    function ($attribute, $value, $fail) {
                        if ($value === true) {
                            if (is_null($this->state['image_id'])) {
                                $fail('You cannot make this application public without an image.');
                            }
                            if (!$this->getModel()->screens()->where('is_default', true)->exists()) {
                                $fail('You cannot make this application public without a default screen.');
                            }
                        }
                    },
                ],
                'callback' => fn($model) => $model->is_public ? 'Yes' : 'No'
            ]
        ];
    }

    protected function modifyQuery(Builder $query): Builder
    {
        return $query->with(['image', 'screens']);
    }

    public function getHasManyFields(): array
    {
        return [
            'screens' => \App\Models\Screen::class
        ];
    }
}
