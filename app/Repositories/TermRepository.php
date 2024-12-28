<?php

namespace App\Repositories;

use App\Models\Gloss;
use App\Models\Term;

class TermRepository
{
    public function findMatchingTerms(string $searchTerm = '', array $filters = [])
    {
        return Term::query()
            ->select('id')
            ->filter(array_merge(['search' => $searchTerm], $filters))
            ->pluck('id');
    }

    public function findMatchingGlosses(string $searchTerm = '', array $filters = [])
    {
        return Gloss::query()
            ->select('id', 'term_id')
            ->filter(array_merge(['search' => $searchTerm], $filters))
            ->get();
    }

    public function searchTerms($terms)
    {
        return Term::query()
            ->whereIn('id', $terms)
            ->with('glosses')
            ->get();
    }

    public function getTerms(array $filter = [], $perPage = 100)
    {
        return Term::query()
            ->select('terms.*')
            ->leftJoin('roots', 'terms.root_id', '=', 'roots.id')
            ->orderByRaw('COALESCE(roots.root, terms.term) ASC')
            ->orderBy('terms.term', 'ASC')
            ->filter($filter)
            ->paginate($perPage)
            ->onEachSide(1);
    }

    public function getLikeTerms(Term $mainTerm): object
    {
        // Get like terms belonging to the same category.
        $duplicates = Term::query()->where('translit', '=', $mainTerm->translit)
            ->where('category', '=', $mainTerm->category)
            ->where('id', '!=', $mainTerm->id)
            ->with('pronunciations')
            ->get();

        // Get like terms belonging to other categories.
        $homophones = Term::query()->where('translit', '=', $mainTerm->translit)
            ->where('category', '!=', $mainTerm->category)
            ->with('pronunciations')
            ->get();

        return (object) [
            'duplicates' => $duplicates,
            'homophones' => $homophones,
        ];
    }
}
