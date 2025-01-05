<?php

namespace App\Repositories;

use App\Models\Sentence;

class SentenceRepository
{
    public function searchSentences($terms, $glosses, string $searchTerm = '')
    {
        $termsFromGlosses = $glosses->pluck('term_id')->unique();
        $glossIds = $glosses->pluck('id');

        $sentencesFromDirectTerms = Sentence::query()
            ->whereHas('terms', fn($query) => $query->whereIn('terms.id', $terms))
            ->with('terms')
            ->get();

        $sentencesFromGlossTerms = Sentence::query()
            ->whereHas('terms', fn($query) => $query->whereIn('terms.id', $termsFromGlosses)
                ->whereIn('sentence_term.gloss_id', $glossIds))
            ->with([
                'terms' => fn($query) => $query->whereHas('glosses',
                    fn($query) => $query->whereIn('glosses.id', $glossIds))
            ])
            ->get();

        $sentencesFromPivotMatch = Sentence::query()
            ->whereHas('terms',
                fn($query) => $query
                    ->where(fn($pivotQuery) => $pivotQuery
                        ->where('sentence_term.sent_term', 'like', '%'.$searchTerm.'%')
                        ->orWhere('sentence_term.sent_translit', 'like', '%'.$searchTerm.'%')
                    )
            )
            ->with('terms')
            ->get();

        return $sentencesFromDirectTerms
            ->merge($sentencesFromGlossTerms)
            ->merge($sentencesFromPivotMatch)
            ->unique('id');
    }
}
