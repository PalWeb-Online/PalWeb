<?php

namespace App\Services;

use App\Repositories\DeckRepository;
use App\Repositories\SentenceRepository;
use App\Repositories\TermRepository;

class SearchService
{
    protected $termRepository;
    protected $sentenceRepository;
    protected $deckRepository;

    public function __construct(
        TermRepository $termRepository,
        SentenceRepository $sentenceRepository,
        DeckRepository $deckRepository
    ) {
        $this->termRepository = $termRepository;
        $this->sentenceRepository = $sentenceRepository;
        $this->deckRepository = $deckRepository;
    }

    public function search(string $searchTerm, bool $withSentences = false, bool $withDecks = false): array
    {
        $terms = $this->termRepository->findMatchingTerms($searchTerm);
        $glosses = $this->termRepository->findMatchingGlosses($searchTerm);

        $results = [
            'terms' => $this->termRepository->searchTerms($terms->merge($glosses->pluck('term_id'))->unique()),
        ];

        if ($withSentences) {
            $results['sentences'] = $this->sentenceRepository->searchSentences($terms, $glosses);
        }

        if ($withDecks) {
            $results['decks'] = $this->deckRepository->searchDecks($terms, $searchTerm);
        }

        return $results;
    }
}
