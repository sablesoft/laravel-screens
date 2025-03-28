<?php

namespace App\Crud\Traits;

use App\Crud\Interfaces\ShouldHasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin ShouldHasMany
 */
trait HandleHasMany
{
    const MULTI_SELECT_COMPONENT = 'crud.multi-searchable';

    protected function hasManyField(string $relation, array $action = ['index', 'view', 'edit']): array
    {
        return [
            'action' => $action,
            'callback' => fn($model) => $this->getHasManyHtml($model, $relation),
            'type' => 'template',
            'template' => static::MULTI_SELECT_COMPONENT,
        ];
    }

    public function templateParams(string $action, ?string $field = null): array
    {
        if (array_key_exists($field, $this->getHasManyFields())) {
            $class = $this->getHasManyFields()[$field];
            return $this->optionsParam($field, $class);
        }

        return [];
    }

    protected function getHasManyHtml(Model $model, string $relation): ?string
    {
        $html = '';
        foreach ($model->$relation as $related) {
            $html .= "<span class='border-2 rounded-full p-1 mr-1'>$related->title</span>";
        }

        return $html ? "<p>$html<p/>" : null;
    }

    protected function syncHasMany(): void
    {
        $model = $this->getResource();
        foreach ($this->getHasManyFields() as $relation => $class) {
            $ids = Arr::get($this->state, $relation);
            $r = $model->$relation();
            if (method_exists($r, 'sync')) {
                $r->sync($ids);
            }
        }
    }

    public function edit(int $id): void
    {
        $model = $this->getModel($id);
        $this->modelId = $id;
        $this->resetState();
        foreach($this->fields('edit') as $field) {
            if (in_array($field, array_keys($this->getHasManyFields()))) {
                $this->state[$field] = $model->$field()->pluck('id')->toArray();
            } else {
                if ($this->type($field) === 'image') {
                    $callback = $this->config($field, 'callback');
                    $this->state[$field] = is_callable($callback) ?
                        $callback($model) : $model->$field;
                } else {
                    $this->state[$field] = $model->$field;
                }
            }
        }
        $this->action = 'edit';
        $this->changeUri('edit', $id);
        $this->openForm();
    }

    public function store(): void
    {
        if ($this->action === 'edit') {
            $this->syncHasMany();
        }

        parent::store();
    }
}
