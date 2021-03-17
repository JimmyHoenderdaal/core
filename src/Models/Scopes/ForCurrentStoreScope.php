<?php

namespace Rapidez\Core\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ForCurrentStoreScope implements Scope
{
    public ?string $joinTable;

    public function __construct($joinTable = null)
    {
        $this->joinTable = $joinTable;
    }

    public function apply(Builder $builder, Model $model)
    {
        $currentTable = $builder->getQuery()->from;
        $joinTable    = $this->joinTable ?: $currentTable.'_store';
        $type         = explode('_', $currentTable)[1];

        $builder
            ->leftJoin($joinTable, function ($join) use ($currentTable, $type, $joinTable) {
                $join->on($currentTable.'.'.$type.'_id', '=', $joinTable.'.'.$type.'_id');
            })
            ->whereIn('store_id', [0, config('rapidez.store')])
            ->orderByDesc('store_id')
            ->limit(1);
    }
}
