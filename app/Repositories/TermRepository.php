<?php

namespace App\Repositories;

use App\Models\Gloss;
use App\Models\Root;
use App\Models\Term;
use Illuminate\Support\Collection;

class TermRepository
{
    public function findMatchingTerms(array $filters = []): Collection
    {
        return Term::query()
            ->select('id AS term_id')
            ->match($filters)
            ->get();
    }

    public function findMatchingGlosses(array $filters = []): Collection
    {
        return Gloss::query()
            ->select('id AS gloss_id', 'term_id')
            ->where(fn ($query) => $query
                ->whereRaw('MATCH(gloss) AGAINST(? IN NATURAL LANGUAGE MODE)', [$filters['search']])
                ->orWhere('gloss', 'like', $filters['search'].'%')
            )
            ->get();
    }

    public function findMatchingRoots(array $filters = []): Collection
    {
        $roots = Root::query()
            ->with('terms')
            ->where('root', 'like', $filters['search'].'%')
            ->get();

        return $roots->flatMap(fn ($root) =>
            $root->terms->map(fn ($term) => ['term_id' => $term->id])
        );
    }

    public function searchTerms($matches, array $filters = []): Collection
    {
        return Term::query()
            ->with(['root', 'glosses'])
            ->whereIn('id', $matches->pluck('term_id'))
            ->filter($filters)
            ->get();
    }

    public function getLikeTerms(Term $mainTerm): object
    {
        // Get like terms belonging to the same category (e.g. ريحة rīħa & ريحا rīħa).
        $duplicates = Term::query()
            ->where('translit', '=', $mainTerm->translit)
            ->where('category', '=', $mainTerm->category)
            ->where('id', '!=', $mainTerm->id)
            ->get();

        // Get like terms belonging to other categories (e.g. adj كثير & adv كثير).
        $homophones = Term::query()
            ->where('translit', '=', $mainTerm->translit)
            ->where('category', '!=', $mainTerm->category)
            ->get();

        return (object) [
            'duplicates' => $duplicates,
            'homophones' => $homophones,
        ];
    }
}
