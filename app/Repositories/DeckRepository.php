<?php

namespace App\Repositories;

use App\Models\Deck;

class DeckRepository
{
    public function searchDecks($terms, string $searchTerm = '')
    {
        return Deck::query()
            ->with(['author'])
            ->withCount('terms')
            ->where(fn ($query) => $query->where('decks.private', false)
                ->orWhere('decks.user_id', auth()->user()->id ?? null)
            )
            ->where(function ($query) use ($terms, $searchTerm) {
                if (! empty($searchTerm)) {
                    $query->where('name', 'like', '%'.$searchTerm.'%');
                }
                $query->orWhereHas('terms', fn ($query) => $query->whereIn('terms.id', $terms));
            })
            ->get();
    }
}
