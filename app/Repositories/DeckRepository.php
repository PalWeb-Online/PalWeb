<?php

namespace App\Repositories;

use App\Models\Deck;
use Illuminate\Support\Collection;

class DeckRepository
{
    public function searchDecks(?Collection $matches, array $filters = []): Collection
    {
        $matches ??= collect();
        $terms = $matches->pluck('term_id')->filter()->unique()->values();

        return Deck::query()
            ->when(trim($filters['search'] ?? '') !== '', fn ($query) => $query
                ->where(function ($query) use ($filters, $terms) {
                    $query->where('name', 'like', '%'.$filters['search'].'%');

                    if ($terms->isNotEmpty()) {
                        $query->orWhereHas('terms', fn ($query) => $query
                            ->whereIn('terms.id', $terms)
                        );
                    }
                })
            )
            ->filter($filters)
            ->get()
            ->unique('id');
    }
}
