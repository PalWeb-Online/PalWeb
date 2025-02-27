<?php

namespace App\Repositories;

use App\Models\Sentence;
use Illuminate\Support\Collection;

class SentenceRepository
{
    public function searchSentences($matches, array $filters = []): Collection
    {
        $terms = $matches->unique('term_id')->pluck('term_id');
        $glosses = $matches->pluck('gloss_id')->filter();

        return Sentence::query()
            ->with(['terms'])
            ->whereHas('terms', fn ($query) => $query
                ->whereIn('terms.id', $terms)
                ->when($glosses->isNotEmpty(), fn ($q) => $q
                    ->whereIn('sentence_term.gloss_id', $glosses)
                )
            )
            ->when(! empty($filters['search']), fn ($query) => $query
                ->orWhereHas('terms', fn ($query) => $query
                    ->where(fn ($pivotQuery) => $pivotQuery
                        ->where('sentence_term.sent_term', 'like', $filters['search'].'%')
                        ->orWhere('sentence_term.sent_translit', 'like', $filters['search'].'%')
                    )
                )
            )
            ->when($filters['pinned'] ?? false, fn ($query) => $query
                ->whereHasBookmark(auth()->user())
            )
            ->get()
            ->unique('id');
    }
}
