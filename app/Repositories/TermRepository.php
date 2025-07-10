<?php

namespace App\Repositories;

use App\Models\Gloss;
use App\Models\Root;
use App\Models\Term;
use Illuminate\Support\Collection;

class TermRepository
{
    public function findMatchingTerms(?string $search): Collection
    {
        return Term::query()
            ->select('terms.id AS term_id')
            ->match($search)
            ->get();
    }

    public function findMatchingGlosses(?string $search): Collection
    {
        return Gloss::query()
            ->select('glosses.id AS gloss_id', 'term_id')
            ->where(fn ($query) => $query
                ->whereRaw('MATCH(gloss) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search])
                ->orWhere('gloss', 'like', $search.'%')
            )
            ->get();
    }

    public function findMatchingRoots(?string $search): Collection
    {
        $roots = Root::query()
            ->with(['terms'])
            ->where('root', 'like', $search.'%')
            ->get();

        return $roots->flatMap(fn ($root) =>
            $root->terms->map(fn ($term) => ['term_id' => $term->id])
        );
    }

    public function searchTerms($matches, array $filters = []): Collection
    {
        return Term::query()
            ->with(['root', 'glosses'])
            ->select('terms.*')
            ->whereIn('terms.id', $matches->pluck('term_id'))
            ->filter($filters)
            ->get();
    }

    public function getLikeTerms(Term $mainTerm): object
    {
        // Get like terms belonging to the same category (e.g. ريحة rīħa & ريحا rīħa).
        $duplicates = Term::query()
            ->where('translit', '=', $mainTerm->translit)
            ->where('category', '=', $mainTerm->category)
            ->where('terms.id', '!=', $mainTerm->id)
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
