<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UnitScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder
            ->when(! auth()->user()->isAdmin(), fn ($q) => $q
                ->where('units.published', true)
            );

        $builder
            ->with(['lessons']);
    }
}
