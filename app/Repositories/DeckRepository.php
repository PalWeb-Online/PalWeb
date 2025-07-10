<?php

namespace App\Repositories;

use App\Models\Deck;
use Illuminate\Support\Collection;

class DeckRepository
{
    public function searchDecks(?Collection $matches, array $filters = []): Collection
    {
        return Deck::query()
            ->when(! empty($filters['search']), fn ($query) => $query
                ->where('name', 'like', '%'.$filters['search'].'%')
                ->orWhereHas('terms', fn ($query) => $query
                    ->whereIn('terms.id', $matches->pluck('term_id'))
                )
            )
            ->filter($filters)
            ->get();
    }
}
