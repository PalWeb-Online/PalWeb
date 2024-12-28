<?php

namespace App\Repositories;

use App\Models\Deck;

class DeckRepository
{
    public function searchDecks($terms, string $searchTerm = '')
    {
        return Deck::query()
            ->where('private', 0)
            ->where(function ($query) use ($terms, $searchTerm) {
                if (!empty($searchTerm)) {
                    $query->where('name', 'like', $searchTerm . '%');
                }
                $query->orWhereHas('terms', fn($query) => $query->whereIn('terms.id', $terms));
            })
            ->with('author')
            ->take(10)
            ->get();
    }
}
