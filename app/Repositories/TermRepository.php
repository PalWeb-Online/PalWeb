<?php

namespace App\Repositories;

use App\Models\Term;

class TermRepository
{
    /**
     * Returns a paginated list of terms filtered by the provided array.
     * This method was pulled from TermController::index
     *
     * @return mixed
     */
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
