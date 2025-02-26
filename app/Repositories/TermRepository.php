<?php

namespace App\Repositories;

use App\Models\Gloss;
use App\Models\Term;
use Illuminate\Support\Collection;

class TermRepository
{
    public function findMatchingTerms(array $filters = []): Collection
    {
        return Term::query()
            ->select('id')
            ->filter($filters)
            ->pluck('id');
    }

    public function findMatchingGlosses(array $filters = []): Collection
    {
        return Gloss::query()
            ->select('term_id')
            ->filter($filters)
            ->pluck('term_id');
    }

    public function searchTerms($terms): Collection
    {
        return Term::query()
            ->whereIn('id', $terms)
            ->with(['root', 'glosses'])
            ->get();
    }

    public function getTerms(array $filter = [], $perPage = 100)
    {
        return Term::query()
            ->select('terms.*')
            ->leftJoin('roots', 'terms.root_id', '=', 'roots.id')
            ->orderByRaw('COALESCE(roots.root, terms.term) ASC')
            ->orderBy('terms.term')
            ->filter($filter)
            ->paginate($perPage)
            ->onEachSide(1);
    }

    public function getLikeTerms(Term $mainTerm): object
    {
        // Get like terms belonging to the same category (e.g. ريحة rīħa & ريحا rīħa).
        $duplicates = Term::query()->where('translit', '=', $mainTerm->translit)
            ->where('category', '=', $mainTerm->category)
            ->where('id', '!=', $mainTerm->id)
            ->with('pronunciations')
            ->get();

        // Get like terms belonging to other categories (e.g. adj كثير & adv كثير).
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
