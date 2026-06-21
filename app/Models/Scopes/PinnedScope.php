<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PinnedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! auth()->check()) {
            return;
        }

        $builder
            ->withExists([
                'bookmarks as is_pinned' => fn ($query) => $query
                    ->where('user_id', auth()->id())
                    ->whereNull('value'),
            ]);
    }
}
