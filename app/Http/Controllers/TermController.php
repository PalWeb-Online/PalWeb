<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Http\Resources\PronunciationResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Http\Resources\TermShowResource;
use App\Models\Attribute;
use App\Models\Gloss;
use App\Models\Inflection;
use App\Models\Pattern;
use App\Models\Pronunciation;
use App\Models\Root;
use App\Models\Spelling;
use App\Models\Term;
use App\Repositories\TermRepository;
use App\Services\SearchService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Maize\Markable\Models\Bookmark;
use Illuminate\Support\Facades\URL;
use Throwable;

class TermController extends Controller
{
    public function __construct(
        protected TermRepository $termRepository,
        protected TermService $termService
    ) {
    }

    public function pin(Request $request, Term $term): JsonResponse
    {
        $user = $request->user();
        Bookmark::toggle($term, $user);

        $isPinned = Bookmark::has($term, $user);

        if ($isPinned) {
            event(new ModelPinned($user));
        }

        return response()->json([
            'isPinned' => $isPinned,
            'message' => $isPinned
                ? __('pin.added', ['thing' => $term->term])
                : __('pin.removed', ['thing' => $term->term]),
        ]);
    }

    public function index(): \Inertia\Response
    {
        $featuredTerm = Cache::get('word-of-the-day') ?? Term::whereNotNull('image')->inRandomOrder()->first();
        $featuredTerm?->load(['attributes', 'glosses.attributes']);

        return Inertia::render('Library/Terms/Index', [
            'featuredTerm' => new TermShowResource($featuredTerm),
        ]);
    }

    public function show(Term $term): \Inertia\Response
    {
        return Inertia::render('Library/Terms/Show', [
            'termId' => $term->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // API Methods
    // -------------------------------------------------------------------------

    public function apiIndex(Request $request, SearchService $searchService): JsonResponse
    {
        URL::forceScheme('https');

        $filters = array_merge(['sort' => 'alphabetical'], $request->only([
            'search', 'match', 'sort', 'pinned', 'letter', 'category', 'attribute', 'form', 'singular', 'plural',
        ]));

        $perPage = 25;
        $currentPage = $request->integer('page', 1);

        $termsCollection = $searchService->search($filters)['terms'];
        $terms = new \Illuminate\Pagination\LengthAwarePaginator(
            $termsCollection->forPage($currentPage, $perPage)->values(),
            $termsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json([
            'terms' => [
                'data' => TermResource::collection($terms)->toArray($request),
                'meta' => [
                    'links' => $terms->linkCollection()->toArray(),
                    'current_page' => $terms->currentPage(),
                    'last_page' => $terms->lastPage(),
                    'total' => $terms->total(),
                ],
            ],
            'totalCount' => $terms->total(),
            'filters' => $filters,
        ]);
    }

    public function fetch(Request $request, Term $term): JsonResponse
    {
        $includes = collect(explode(',', (string) $request->query('include')))
            ->map(fn (string $include) => trim($include))
            ->filter()
            ->values();

        $termIds = $this->termRepository->getLikeTermIds($term);

        $query = Term::query()
            ->whereIn('id', $termIds);

        if ($includes->contains('show') || $includes->isEmpty()) {
            $query->with([
                'root',
                'pronunciations.audios.speaker',
                'attributes',
                'spellings',
                'relatives',
                'patterns',
                'glosses.attributes',
                'inflections',
                'cards',
                'decks' => fn ($q) => $q->limit(10),
            ])
                ->withCount('pronunciations');
        }

        $terms = $query->get();

        $this->termService->hydrateGlossSentences($terms);

        return response()->json([
            'terms' => TermShowResource::collection($terms)
        ]);
    }

    // -------------------------------------------------------------------------

    public function getPronunciations(Term $term): AnonymousResourceCollection
    {
        $pronunciations = $term->pronunciations()
            ->with([
                'audios' => fn ($query) => $query
                    ->limit(1)
                    ->with(['speaker']),
            ])
            ->withCount('audios')
            ->get();

        return PronunciationResource::collection($pronunciations);
    }

    public function getSentences(Term $term, $glossId): AnonymousResourceCollection
    {
        $sentences = $term->sentences($glossId)->get();

        return SentenceResource::collection($sentences);
    }

    public function store(StoreTermRequest $request): RedirectResponse
    {
        $term = DB::transaction(function () use ($request) {
            $formData = $request->all();

            if ($formData['root']['root']) {
                $formData = array_merge($formData,
                    ['root_id' => Root::firstOrCreate(['root' => $formData['root']['root']])->id]);
            }

            $formData = array_merge($formData, [
                'translit' => $formData['pronunciations'][0]['translit'],
                'slug' => $this->handleSlug($formData['category'], $formData['pronunciations'][0]['translit']),
            ]);

            $term = Term::create($formData);

            $attributes = array_map(fn ($item) => $item['attribute'], $request->input('attributes'));
            foreach ($attributes as $attribute) {
                Attribute::firstWhere('attribute', $attribute)->terms()->attach($term);
            }

            $this->handleRelatives($term, $formData['relatives']);
            $this->handlePatterns($term, $formData['patterns']);

            $this->handleDependents($term, $formData['spellings'], Spelling::class);
            $this->handleDependents($term, $formData['inflections'], Inflection::class);
            $this->handleDependents($term, $formData['pronunciations'], Pronunciation::class);

            foreach ($request->glosses as $glossData) {
                $glossData = array_merge($glossData, ['term_id' => $term->id]);
                $gloss = Gloss::create($glossData);

                $glossAttributes = array_map(fn ($item) => $item['attribute'], $glossData['attributes']);
                foreach ($glossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->attach($gloss);
                }
            }

            return $term;
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $term->term])]);

        return to_route('terms.show', $term);
    }

    public function update(Term $term, UpdateTermRequest $request): RedirectResponse
    {
        $term = DB::transaction(function () use ($term, $request) {
            $formData = $request->all();

            if ($formData['root']['root']) {
                $formData = array_merge($formData,
                    ['root_id' => Root::firstOrCreate(['root' => $formData['root']['root']])->id]);
            } else {
                $formData = array_merge($formData, ['root_id' => null]);
            }

            $term->update($formData);
            $term->refresh();

            $this->handleAttributes($term, $formData['attributes'], 'terms');
            $this->handleRelatives($term, $formData['relatives']);
            $this->handlePatterns($term, $formData['patterns']);

            $this->handleDependents($term, $formData['spellings'], Spelling::class, $term->spellings);
            $this->handleDependents($term, $formData['inflections'], Inflection::class, $term->inflections);
            $this->handleDependents($term, $formData['pronunciations'], Pronunciation::class, $term->pronunciations);

            $term->update([
                'translit' => $formData['pronunciations'][0]['translit'],
                'slug' => $this->handleSlug($formData['category'], $formData['pronunciations'][0]['translit'], $term),
            ]);

            $requestGlosses = collect($formData['glosses']);
            $existingGlosses = $term->glosses->keyBy('id');

            foreach ($requestGlosses as $glossData) {
                $id = $glossData['id'] ?? null;
                if ($id && $existingGlosses->has($id)) {
                    $existingGlosses[$id]->update($glossData);
                    $gloss = $existingGlosses[$id];
                } else {
                    $gloss = Gloss::create(array_merge($glossData, ['term_id' => $term->id]));
                }
                $this->handleAttributes($gloss, $glossData['attributes'], 'glosses');
            }

            $existingGlosses->each(function ($gloss) use ($requestGlosses) {
                if (! $requestGlosses->pluck('id')->contains($gloss->id)) {
                    $gloss->delete();
                }
            });

            Root::doesntHave('terms')->delete();

            return $term;
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $term->term])]);

        return to_route('terms.show', $term);
    }

    public function handleSlug($category, $translit, ?Term $term = null): string
    {
        $slug = $category.'-'.$translit;

        $duplicatesQuery = Term::where([
            'category' => $category,
            'translit' => $translit,
        ]);

        $term && $duplicatesQuery = $duplicatesQuery->where('id', '!=', $term->id);
        $count = $duplicatesQuery->count();

        if ($count > 0) {
            foreach ($duplicatesQuery->get() as $i => $duplicate) {
                $duplicate->update(['slug' => $duplicate->category.'-'.$duplicate->translit.'-'.($i + 1)]);
            }
            $slug .= '-'.$count + 1;
        }

        return $slug;
    }

    private function handlePatterns($term, $requestPatterns): void
    {
        $attachedPatternIds = $term->patterns->pluck('id')->toArray();

        $requestPatternIds = [];
        foreach ($requestPatterns as $requestPattern) {
            $pattern = Pattern::firstWhere([
                ['type', '=', $requestPattern['type']],
                ['form', '=', $requestPattern['form']],
                ['pattern', '=', $requestPattern['pattern']],
            ]);
            $requestPatternIds[] = $pattern->id;
            $pattern->terms()->syncWithoutDetaching($term->id);
        }

        $detachablePatternIds = array_diff($attachedPatternIds, $requestPatternIds);
        foreach ($detachablePatternIds as $id) {
            Pattern::find($id)->terms()->detach($term);
        }
    }

    public function handleAttributes(object $model, array $attributes, string $relation): void
    {
        $requestAttributes = array_map(fn ($item) => $item['attribute'], $attributes);
        foreach ($requestAttributes as $attribute) {
            Attribute::firstWhere('attribute', $attribute)->{$relation}()->syncWithoutDetaching($model->id);
        }

        $detachableAttributes = array_diff($model->attributes->pluck('attribute')->toArray(), $requestAttributes);
        foreach ($detachableAttributes as $attribute) {
            Attribute::firstWhere('attribute', $attribute)->{$relation}()->detach($model);
        }
    }

    private function handleRelatives(Term $term, array $relatives): void
    {
        $attachedTerms = $term->relatives->pluck('slug')->toArray();

        $requestTerms = [];
        foreach ($relatives as $relative) {
            $relativeTerm = Term::firstWhere('slug', $relative['slug']);
            $requestTerms[] = $relativeTerm->slug;

            if (! in_array($relativeTerm->slug, $attachedTerms)) {
                $term->relatives()->attach($relativeTerm, [
                    'type' => $relative['type'],
                    'gloss_id' => $relative['gloss_id'] ?? null,
                ]);

                switch ($relative['type']) {
                    default:
                        $relativeTerm->relatives()->attach($term, ['type' => $relative['type']]);
                        break;
                    case 'component':
                        $relativeTerm->relatives()->attach($term, ['type' => 'descendant']);
                        break;
                    case 'descendant':
                        $relativeTerm->relatives()->attach($term, ['type' => 'component']);
                        break;
                    case in_array($relative['type'], ['ap', 'pp', 'vn']):
                        $relativeTerm->relatives()->attach($term, ['type' => 'source']);
                        break;
                }
            } else {
                $term->relatives()->updateExistingPivot($relativeTerm->id, [
                    'type' => $relative['type'],
                    'gloss_id' => $relative['gloss_id'] ?? null,
                ]);
            }
        }

        $detachableSlugs = array_diff($attachedTerms, $requestTerms);
        foreach ($detachableSlugs as $slug) {
            $detachableTerm = Term::firstWhere('slug', $slug);
            $term->relatives()->detach($detachableTerm);
            $detachableTerm->relatives()->detach($term);
        }
    }

    private function handleDependents(
        object $term,
        array $requestDependents,
        string $model,
        Collection $existingDependents = new Collection,
    ): void {
        $requestItems = [];
        foreach ($requestDependents as $index => $item) {
            unset($item['id']);

            if ($index + 1 <= $existingDependents->count()) {
                $existingDependents[$index]->update($item);
                $dependent = $existingDependents[$index];
            } else {
                $dependent = $model::create(array_merge($item, ['term_id' => $term->id]));
            }

            $requestItems[] = $dependent->id;
        }

        $existingDependents->except($requestItems)->each->delete();
    }

    public function destroy(Term $term): JsonResponse
    {
        try {
            Gate::authorize('delete', $term);

            $deletedTerm = $term->term;

            DB::transaction(function () use ($term) {
                $category = $term->category;
                $translit = $term->translit;

                $term->delete();
                Root::doesntHave('terms')->delete();

                $remainingTerms = Term::where([
                    'category' => $category,
                    'translit' => $translit,
                ])->get();

                if (count($remainingTerms) == 1) {
                    $remainingTerms->first()->update(['slug' => $category.'-'.$translit]);
                } elseif (count($remainingTerms) > 1) {
                    foreach ($remainingTerms as $i => $remainingTerm) {
                        $remainingTerm->update(['slug' => $category.'-'.$translit.'-'.($i + 1)]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => __('deleted', ['thing' => $deletedTerm]),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Term.', [
                'term_id' => $term->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Term.', $e);
        }
    }
}
