<?php

namespace App\Models\Scopes;

use DB;
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
//        if (app()->runningInConsole()) return;

        $user = auth()->user();

        if ($user && $user->isAdmin()) return;

        $table = $model->getTable();

        $builder->where($table.'.published', true);

        if ($table === 'lessons') {
            $builder->where(function ($query) {
                $query->whereNull('unit_id')
                    ->orWhereIn('unit_id', function ($subquery) {
                        $subquery->select('id')
                            ->from('units')
                            ->where('published', true);
                    });
            });
        }

        if ($table === 'activities' || $table === 'dialogs') {
            $column = $table === 'activities' ? 'activity_id' : 'dialog_id';

            $builder->where(function ($query) use ($column) {
                $query->whereNotExists(function ($subquery) use ($column) {
                    $subquery->select(DB::raw(1))
                        ->from('lessons')
                        ->whereColumn('lessons.'.$column, 'id')
                        ->where('published', false);
                });
            });
        }
    }
}
