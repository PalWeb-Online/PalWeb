<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DeckScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        $builder
            ->when(! $user || ! $user->isAdmin(), fn ($query) => $query
                ->where(function ($q) use ($user) {
                    $q->where('decks.private', false);

                    if ($user) {
                        $q->orWhere('decks.user_id', $user->id);
                    }
                })
            );

        $builder
            ->with(['author', 'lesson'])
            ->withCount('terms');
    }
}
