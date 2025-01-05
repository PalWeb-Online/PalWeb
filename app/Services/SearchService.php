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

    public function search(string $searchTerm = '', array $filters = [], bool $withSentences = false, bool $withDecks = false): array
    {
        $terms = $this->termRepository->findMatchingTerms($searchTerm, $filters);
        $glosses = $this->termRepository->findMatchingGlosses($searchTerm, $filters);

        $results = [
            'terms' => $this->termRepository->searchTerms($terms->merge($glosses->pluck('term_id'))->unique()),
        ];

        if ($withSentences) {
            $results['sentences'] = $this->sentenceRepository->searchSentences($terms, $glosses, $searchTerm);
        }

        if ($withDecks) {
            $results['decks'] = $this->deckRepository->searchDecks($terms, $searchTerm);
        }

        return $results;
    }
}
