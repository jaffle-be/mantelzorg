<?php namespace App\System\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

class ModelAutoSortScope implements ScopeInterface
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $field = 'sort';

        $order = 'asc';

        if(property_exists(get_class($model), 'autosort'))
        {
            $reflection = new \ReflectionClass(get_class($model));
            $property = $reflection->getProperty('autosort');

            if(!$property->isPublic())
            {
                throw new \InvalidArgumentException('autosort needs to be public to retrieve the value');
            }

            $autosort = (array) $model->autosort;

            $field = $autosort[0];

            $order = isset($autosort[1]) ? $autosort[1] : $order;
        }

        $builder->orderBy($field, $order);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();
    }

}