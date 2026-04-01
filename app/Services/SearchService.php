<?php

namespace App\Services;

use App\Models\Term;
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

    public function search(array $filters = [], bool $withSentences = false, bool $withDecks = false): array
    {
        $filters['search'] ??= '';
        $filters['match'] ??= 'term';

        $matches = collect();

        if (trim($filters['search']) === '') {
            $results = [
                'terms' => $this->termRepository->allTerms($filters)
            ];

        } else {
            $matches = match ($filters['match']) {
                'root' => $this->termRepository->findMatchingRoots($filters['search']),
                'gloss' => $this->termRepository->findMatchingGlosses($filters['search']),
                default => $this->termRepository->findMatchingTerms($filters['search']),
            };

            $results = [
                'terms' => $this->termRepository->searchTerms($matches, $filters),
            ];
        }

        if ($withDecks) {
            $results['decks'] = $this->deckRepository->searchDecks($matches, $filters);
        }

        if ($withSentences) {
            $results['sentences'] = $this->sentenceRepository->searchSentences($matches, $filters);
        }

        return $results;
    }
}
