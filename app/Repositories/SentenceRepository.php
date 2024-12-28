<?php

namespace App\Repositories;

use App\Models\Sentence;

class SentenceRepository
{
    public function searchSentences($terms, $glosses)
    {
        $termsFromGlosses = $glosses->pluck('term_id')->unique();
        $glossIds = $glosses->pluck('id');

        $sentencesFromDirectTerms = Sentence::query()
            ->whereHas('terms', fn($query) => $query->whereIn('terms.id', $terms))
            ->with('terms')
            ->take(10)
            ->get();

        $sentencesFromGlossTerms = Sentence::query()
            ->whereHas('terms', fn($query) => $query->whereIn('terms.id', $termsFromGlosses)
                ->whereIn('sentence_term.gloss_id', $glossIds))
            ->with([
                'terms' => fn($query) => $query->whereHas('glosses',
                    fn($query) => $query->whereIn('glosses.id', $glossIds))
            ])
            ->take(10)
            ->get();

        return $sentencesFromDirectTerms->merge($sentencesFromGlossTerms)->unique('id')->take(10);
    }
}
