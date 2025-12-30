<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return;
        }

        $table = $model->getTable();

        $builder->where($table.'.published', true);

        if ($table === 'lessons') {
            $builder->where(function ($query) {
                $query->whereDoesntHave('unit')
                    ->orWhereHas('unit', fn ($q) => $q->where('published', true));
            });
        }

        if ($table === 'activities' || $table === 'dialogs') {
            $builder->where(function ($query) {
                $query->whereDoesntHave('lesson')
                    ->orWhereHas('lesson', fn ($q) => $q->where('published', true));
            });
        }
    }
}
