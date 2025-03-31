<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Http\Resources\PronunciationResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
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
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Maize\Markable\Models\Bookmark;

class TermController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
        protected TermRepository $termRepository
    ) {
    }

    public function pin(Request $request, Term $term): JsonResponse
    {
        $user = $request->user();

        Bookmark::toggle($term, $user);

        $term->isPinned() && event(new ModelPinned($user));

        return response()->json([
            'isPinned' => $term->isPinned(),
            'message' => $term->isPinned()
                ? __('pin.added', ['thing' => $term->term])
                : __('pin.removed', ['thing' => $term->term]),
        ]);
    }

    public function index(Request $request, SearchService $searchService): \Inertia\Response
    {
        $filters = $request->only([
            'search', 'match', 'pinned', 'letter', 'category', 'attribute', 'form', 'singular', 'plural'
        ]);

        if (! collect($filters)->except(['match', 'pinned', 'letter'])->some(fn ($value) => ! empty($value))) {
            $terms = Term::query()
                ->with(['root', 'glosses'])
                ->leftJoin('roots', 'terms.root_id', '=', 'roots.id')
                ->orderByRaw('IFNULL(roots.root, terms.term) ASC, roots.root IS NULL, terms.term ASC')
                ->select('terms.*')
                ->filter($filters)
                ->paginate(25)
                ->onEachSide(1)
                ->appends($filters);
            $totalCount = $terms->total();

        } else {
            $results = $searchService->search($filters)['terms'];
            $totalCount = $results->count();

            $perPage = 25;
            $currentPage = $request->input('page', 1);
            $terms = $results->forPage($currentPage, $perPage);

            $terms = new \Illuminate\Pagination\LengthAwarePaginator(
                $terms,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        $featuredTerm = Cache::get('word-of-the-day');

        $featuredTerm
            ? $featuredTerm = new TermResource($featuredTerm)
            : $featuredTerm = new TermResource(Term::whereNotNull('image')->inRandomOrder()->first());

        return Inertia::render('Library/Terms/Index', [
            'section' => 'library',
            'terms' => TermResource::collection($terms),
            'totalCount' => $totalCount,
            'latestTerms' => Term::with(['glosses'])->orderByDesc('id')->take(10)->get(),
            'featuredTerm' => $featuredTerm ?? null,
            'filters' => $filters,
        ]);

//        View::share('pageDescription',
//            'Discover the PalWeb Dictionary, an extensive, practical & fun-to-use online dictionary for Levantine Arabic, complete with pronunciation audios & example sentences. Boost your Palestinian Arabic vocabulary now!');
    }

    public function show(Term $term): \Inertia\Response
    {
        $likeTerms = $this->termRepository->getLikeTerms($term);
        $terms = collect([$term, ...$likeTerms->duplicates, ...$likeTerms->homophones])->filter();

        foreach ($terms as $model) {
            $model
                ->load([
                    'root',
                    'attributes',
                    'spellings',
                    'relatives',
                    'patterns',
                    'glosses' => function ($query) {
                        $query->with([
                            'attributes',
                            'relatives'
                        ]);
                    },
                    'inflections',
                    'decks',
                ])
                ->loadCount(['pronunciations']);

            $model->gloss_sentences = $model->getSingleGlossSentence();
        }

        return Inertia::render('Library/Terms/Show', [
            'section' => 'library',
            'terms' => TermResource::collection(
                $terms->map(function ($term) {
                    return new TermResource($term)->additional(['detail' => true]);
                })
            ),
        ]);

//        View::share('pageDescription',
//            'Discover an extensive, practical & fun-to-use online dictionary for Levantine Arabic, complete with pronunciation audios & example sentences. Boost your Palestinian Arabic vocabulary now!');
    }

    public function getPronunciations(Term $term): AnonymousResourceCollection
    {
        $pronunciations = $term->pronunciations()
            ->with([
                'audios' => fn ($query) => $query
                    ->limit(1)
                    ->with(['speaker.user'])
            ])
            ->withCount('audios')
            ->get();

        return PronunciationResource::collection($pronunciations);
    }

    public function getSentences(Term $term, $glossId): AnonymousResourceCollection
    {
        $sentences = $term->sentences()
            ->where('gloss_id', $glossId)
            ->get();

        return SentenceResource::collection($sentences);
    }

    public function create(): \Inertia\Response
    {
        View::share('pageTitle', 'Create Term');

        return Inertia::render('Library/Terms/Create', [
            'section' => 'library',
        ]);
    }

    public function edit(Term $term): \Inertia\Response
    {
        View::share('pageTitle', 'Edit Term');

        $term->load([
            'root',
            'attributes',
            'spellings',
            'relatives',
            'patterns',
            'glosses' => function ($query) {
                $query->with([
                    'attributes',
                    'relatives'
                ]);
            },
            'inflections',
        ]);

        return Inertia::render('Library/Terms/Create', [
            'section' => 'library',
            'term' => new TermResource($term)->additional(['detail' => true]),
        ]);
    }

    public function store(StoreTermRequest $request): RedirectResponse
    {
        $term = DB::transaction(function () use ($request) {
            $formData = $request->term;

            if ($formData['root']['root']) {
                $formData = array_merge($formData,
                    ['root_id' => Root::firstOrCreate(['root' => $formData['root']['root']])->id]);
            }

            $formData = array_merge($formData, [
                'translit' => $formData['pronunciations'][0]['translit'],
                'slug' => $this->handleSlug($formData['category'], $formData['pronunciations'][0]['translit']),
            ]);

            $term = Term::create($formData);

            $attributes = array_map(fn ($item) => $item['attribute'], $request->term['attributes']);
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

                $this->handleRelatives($gloss, $glossData['relatives']);
            }

            return $term;
        });

        return to_route('terms.show', $term);
    }

    public function update(Term $term, UpdateTermRequest $request): RedirectResponse
    {
        $term = DB::transaction(function () use ($term, $request) {
            $formData = $request->term;

            if ($formData['root']['root']) {
                $formData = array_merge($formData,
                    ['root_id' => Root::firstOrCreate(['root' => $formData['root']['root']])->id]);
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

            $requestGlosses = [];
            foreach ($formData['glosses'] as $index => $glossData) {
                unset($glossData['id']);
                $requestGlosses[] = $glossData['gloss'];

                if ($index + 1 <= count($term->glosses)) {
                    $term->glosses[$index]->update($glossData);
                    $gloss = $term->glosses[$index];

                } else {
                    $glossData = array_merge($glossData, ['term_id' => $term->id]);
                    $gloss = Gloss::create($glossData);
                }

                $this->handleAttributes($gloss, $glossData['attributes'], 'glosses');
                $this->handleRelatives($gloss, $glossData['relatives']);
            }

            foreach ($term->glosses as $gloss) {
                ! in_array($gloss->gloss, $requestGlosses) && $gloss->delete();
            }

            Root::doesntHave('terms')->delete();

            return $term;
        });

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

    function handleAttributes(object $model, array $attributes, string $relation): void
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

    private function handleRelatives(object $model, array $relatives): void
    {
        $attachedTerms = $model->relatives->pluck('slug')->toArray();

        $requestTerms = [];
        foreach ($relatives as $relative) {
            $term = Term::firstWhere('slug', $relative['slug']);

            $requestTerms[] = $term->slug;

            if (! in_array($term->slug, $attachedTerms)) {
                $model->relatives()->attach($term, ['type' => $relative['type']]);

                switch ($relative['type']) {
                    default:
                        $term->relatives()->attach($model, ['type' => $relative['type']]);
                        break;
                    case 'component':
                        $term->relatives()->attach($model, ['type' => 'descendant']);
                        break;
                    case 'descendant':
                        $term->relatives()->attach($model, ['type' => 'component']);
                        break;
                }
            }
        }

        $detachableSlugs = array_diff($attachedTerms, $requestTerms);
        foreach ($detachableSlugs as $slug) {
            $term = Term::firstWhere('slug', $slug);
            $model->relatives()->detach($term);

            if ($model instanceof Term) {
                $term->relatives()->detach($model);
            }
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

    public function destroy(Term $term): RedirectResponse
    {
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

        return to_route('terms.index');
    }
}
