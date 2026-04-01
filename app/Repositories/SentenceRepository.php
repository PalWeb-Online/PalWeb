<?php

namespace App\Repositories;

use App\Models\Sentence;
use Illuminate\Support\Collection;

class SentenceRepository
{
    public function searchSentences(?Collection $matches, array $filters = []): Collection
    {
        $matches ??= collect();

        $terms = $matches->pluck('term_id')->filter()->unique()->values();
        $glosses = $matches->pluck('gloss_id')->filter()->unique()->values();

        return Sentence::query()
            ->with(['terms'])
            ->when($terms->isNotEmpty(), fn ($query) => $query
                ->whereHas('terms', fn ($query) => $query
                    ->whereIn('terms.id', $terms)
                    ->when($glosses->isNotEmpty(), fn ($q) => $q
                        ->whereIn('sentence_term.gloss_id', $glosses)
                    )
                ))
            ->when($terms->isEmpty(), fn ($query) => $query)
            ->when(trim($filters['search'] ?? '') !== '', fn ($query) => $query
                ->orWhereHas('terms', fn ($query) => $query
                    ->where(fn ($pivotQuery) => $pivotQuery
                        ->where('sentence_term.sent_term', 'like', $filters['search'].'%')
                        ->orWhere('sentence_term.sent_translit', 'like', $filters['search'].'%')
                    )
                )
            )
            ->filter($filters)
            ->get()
            ->unique('id');
    }
}
