<?php

namespace App\Repositories;

use App\Models\Gloss;
use App\Models\Root;
use App\Models\Term;
use App\Services\TermService;
use Illuminate\Support\Collection;

class TermRepository
{
    public function __construct(
        protected TermService $termService
    ) {
    }

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

        return $roots->flatMap(fn ($root) => $root->terms->map(fn ($term) => ['term_id' => $term->id])
        );
    }

    public function allTerms(array $filters = []): Collection
    {
        return Term::query()
            ->withItemData()
            ->withUserCard()
            ->with(['root'])
            ->select('terms.*')
            ->filter($filters)
            ->get()
            ->tap(fn ($terms) => $this->termService->hydratePronunciations($terms));
    }

    public function searchTerms(Collection $matches, array $filters = []): Collection
    {
        return Term::query()
            ->withItemData()
            ->withUserCard()
            ->with(['root'])
            ->select('terms.*')
            ->whereIn('terms.id', $matches->pluck('term_id'))
            ->filter($filters)
            ->get()
            ->tap(fn ($terms) => $this->termService->hydratePronunciations($terms));
    }

    public function getLikeTermIds(Term $term): object
    {
        $likeTermIds = Term::query()
            ->where('translit', $term->translit)
            ->where('id', '!=', $term->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$term->category])
            ->pluck('id');

        return collect([$term->id])
            ->merge($likeTermIds)
            ->unique()
            ->values();
    }
}
