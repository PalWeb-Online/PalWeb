<?php

namespace App\Repositories;

use App\Models\Deck;
use Illuminate\Support\Collection;

class DeckRepository
{
    public function searchDecks($matches, array $filters = []): Collection
    {
        return Deck::query()
            ->with(['author'])
            ->withCount('terms')
            ->where(fn ($query) => $query
                ->where('decks.private', false)
                ->orWhere('decks.user_id', auth()->user()?->id)
            )
            ->when($filters['search'] ?? false, fn ($query) => $query
                ->where('name', 'like', '%'.$filters['search'].'%')
                ->orWhereHas('terms', fn ($query) => $query
                    ->whereIn('terms.id', $matches->pluck('term_id'))
                )
            )
            ->when($filters['pinned'] ?? false, fn ($query) => $query
                ->whereHasBookmark(auth()->user())
            )
            ->get();
    }
}
