<?php

namespace App\System\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ModelAutoSortScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $field = 'sort';

        $order = 'asc';

        if (property_exists(get_class($model), 'autosort')) {
            $reflection = new \ReflectionClass(get_class($model));
            $property = $reflection->getProperty('autosort');

            if (!$property->isPublic()) {
                throw new \InvalidArgumentException('autosort needs to be public to retrieve the value');
            }

            $autosort = (array) $model->autosort;

            $field = $autosort[0];

            $order = isset($autosort[1]) ? $autosort[1] : $order;
        }

        $builder->orderBy($field, $order);
    }
}
