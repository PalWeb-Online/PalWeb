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
use App\Models\MissingTerm;
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

//    public function get($id): JsonResponse
//    {
//        $term = Term::with([
//            'root',
//            'patterns',
//            'attributes',
//            'pronunciations',
//            'relatives',
//            'spellings',
//            'inflections',
//            'glosses' => function ($query) {
//                $query->with([
//                    'attributes',
//                    'relatives',
//                ]);
//            },
//        ])->findOrFail($id);
//
//        return response()->json([
//            'term' => $term,
//            'root' => $term->root->root ?? '',
//            'singPatterns' => $term->patterns->where('type', 'singular')->values(),
//            'plurPatterns' => $term->patterns->where('type', 'plural')->values(),
//            'verbPatterns' => $term->patterns->where('type', 'verbal')->values(),
//            'pronunciations' => $term->pronunciations,
//            'variants' => $term->variants->map(function ($relative) {
//                return [
//                    'slug' => $relative->slug,
//                    'relation' => $relative->pivot->type,
//                ];
//            }),
//            'references' => $term->references->map(function ($relative) {
//                return [
//                    'slug' => $relative->slug,
//                    'relation' => $relative->pivot->type,
//                ];
//            }),
//            'components' => $term->components->map(function ($relative) {
//                return [
//                    'slug' => $relative->slug,
//                    'relation' => $relative->pivot->type,
//                ];
//            }),
//            'descendants' => $term->descendants->map(function ($relative) {
//                return [
//                    'slug' => $relative->slug,
//                    'relation' => $relative->pivot->type,
//                ];
//            }),
//            'spellings' => $term->spellings,
//            'inflections' => $term->inflections,
//            'glosses' => $term->glosses->map(function ($gloss) {
//                return [
//                    'id' => $gloss->id,
//                    'gloss' => $gloss->gloss,
//                    'attributes' => $gloss->attributes,
//                    'relatives' => $gloss->relatives->map(function ($relative) {
//                        return [
//                            'slug' => $relative->slug,
//                            'relation' => $relative->pivot->type,
//                        ];
//                    }),
//                ];
//            }),
//        ]);
//    }

    public function show(Term $term, Request $request): \Inertia\Response
    {
        $term->load(['root']);

        if ($term->root) {
            $rootData = $term->root->generateRoot($term);

            $root = [
                'ar' => $rootData['ar'],
                'en' => $rootData['en'],
                'all' => $rootData['all'],
                'terms' => $term->root->terms->sortBy('term')->map(function ($term) {
                    return [
                        'id' => $term->id,
                        'slug' => $term->slug,
                        'term' => $term->term,
                        'translit' => $term->translit,
                    ];
                })
            ];
        }

        $likeTerms = $this->termRepository->getLikeTerms($term);
        $terms = collect([$term, ...$likeTerms->duplicates, ...$likeTerms->homophones])->filter();

        foreach ($terms as $model) {
            $model
                ->load([
                    'attributes',
                    'spellings',
                    'variants',
                    'references',
                    'components',
                    'descendants',
                    'patterns',
                    'glosses' => function ($query) {
                        $query->with([
                            'attributes',
                            'synonyms',
                            'antonyms',
                            'valences'
                        ]);
                    },
                    'inflections',
                    'decks',
                ])
                ->loadCount(['pronunciations']);

            $model->sentences = $model->getSingleGlossSentence();
        }

        return Inertia::render('Library/Terms/Show', [
            'section' => 'library',
            'root' => $root ?? null,
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

    public function create(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Create Term');

        return view('terms.create');
    }

    public function store(StoreTermRequest $request): JsonResponse
    {
        $term = DB::transaction(function () use ($request) {

            $termData = $request->term;

            $termData = array_merge($termData, [
                'translit' => $request->pronunciations[0]['translit'],
                'slug' => $this->handleSlug($request->term['category'], $request->pronunciations[0]['translit']),
            ]);

            if ($request->root) {
                $root = Root::firstOrCreate(['root' => $request->root]);
                $termData = array_merge($termData, [
                    'root_id' => $root->id,
                ]);
            }

            $term = Term::create($termData);

            $attributes = array_map(fn ($item) => $item['attribute'], $request->term['attributes']);
            foreach ($attributes as $attribute) {
                Attribute::firstWhere('attribute', $attribute)->terms()->attach($term);
            }

            $this->handlePatterns($term, $request->singPatterns, 'singular');
            $this->handlePatterns($term, $request->plurPatterns, 'plural');
            $this->handlePatterns($term, $request->verbPatterns, 'verbal');

            $relatives = array_merge(
                $request->variants,
                $request->references,
                $request->components,
                $request->descendants,
            );
            $this->handleRelatives($term, $relatives);

            $this->handleDependents($term, $request->spellings, Spelling::class);
            $this->handleDependents($term, $request->inflections, Inflection::class);
            $this->handleDependents($term, $request->pronunciations, Pronunciation::class);

            foreach ($request->glosses as $requestGloss) {
                $requestGloss = array_merge($requestGloss, ['term_id' => $term->id]);
                $gloss = Gloss::create($requestGloss);

                $glossAttributes = array_map(fn ($item) => $item['attribute'], $requestGloss['attributes']);
                foreach ($glossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->attach($gloss);
                }

                $this->handleRelatives($gloss, $requestGloss['relatives']);
            }

            return $term;
        });

        return response()->json([
            'term' => $term,
            'flash' => __('created', ['thing' => $term->term]),
            'redirect' => route('terms.show', $term),
        ]);
    }

    public function update(Term $term, UpdateTermRequest $request): JsonResponse
    {
        $term = DB::transaction(function () use ($term, $request) {

            $termData = $request->term;

            // TODO: double-check this
            if ($request->root) {
                $root = Root::firstOrCreate(['root' => $request->root]);
                $termData = array_merge($termData, [
                    'root_id' => $root->id,
                ]);
            } else {
                $termData = array_merge($termData, [
                    'root_id' => null,
                ]);
            }

            $term->update($termData);
            $term->refresh();

            $requestAttributes = array_map(fn ($item) => $item['attribute'], $request->term['attributes']);
            foreach ($requestAttributes as $attribute) {
                Attribute::firstWhere('attribute', $attribute)->terms()->syncWithoutDetaching($term->id);
            }

            $detachableAttributes = array_diff($term->attributes->pluck('attribute')->toArray(), $requestAttributes);
            foreach ($detachableAttributes as $attribute) {
                Attribute::firstWhere('attribute', $attribute)->terms()->detach($term);
            }

            $this->handlePatterns($term, $request->singPatterns, 'singular');
            $this->handlePatterns($term, $request->plurPatterns, 'plural');
            $this->handlePatterns($term, $request->verbPatterns, 'verbal');

            $relatives = array_merge(
                $request->variants,
                $request->references,
                $request->components,
                $request->descendants,
            );
            $this->handleRelatives($term, $relatives);

            $this->handleDependents($term, $request->spellings, Spelling::class, $term->spellings, 'spelling');
            $this->handleDependents($term, $request->inflections, Inflection::class, $term->inflections, 'translit');
            $this->handleDependents($term, $request->pronunciations, Pronunciation::class, $term->pronunciations,
                'translit');

            $term->update([
                'translit' => $request->pronunciations[0]['translit'],
                'slug' => $this->handleSlug($request->term['category'], $request->pronunciations[0]['translit'], $term),
            ]);

            $requestGlosses = [];
            foreach ($request->glosses as $index => $requestGloss) {
                unset($requestGloss['id']);
                $requestGlosses[] = $requestGloss['gloss'];

                if ($index + 1 <= count($term->glosses)) {
                    $term->glosses[$index]->update($requestGloss);
                    $gloss = $term->glosses[$index];

                } else {
                    $requestGloss = array_merge($requestGloss, ['term_id' => $term->id]);
                    $gloss = Gloss::create($requestGloss);
                }

                $requestGlossAttributes = array_map(fn ($item) => $item['attribute'], $requestGloss['attributes']);
                foreach ($requestGlossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->syncWithoutDetaching($gloss->id);
                }

                $detachableGlossAttributes = array_diff($gloss->attributes->pluck('attribute')->toArray(),
                    $requestGlossAttributes);
                foreach ($detachableGlossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->detach($gloss);
                }

                $this->handleRelatives($gloss, $requestGloss['relatives']);
            }

            foreach ($term->glosses as $gloss) {
                ! in_array($gloss->gloss, $requestGlosses) && $gloss->delete();
            }

            Root::doesntHave('terms')->delete();

            return $term;
        });

        return response()->json([
            'term' => $term,
            'flash' => __('updated', ['thing' => $term->term]),
            'redirect' => route('terms.show', $term),
        ]);
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

    private function handlePatterns($term, $requestPatterns, $type): void
    {
        $attachedPatternIds = $term->patterns->where('type', '=', $type)->pluck('id')->toArray();
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

    private function handleRelatives(object $origin, array $requestItems): void
    {
        $attachedTerms = $origin->relatives->pluck('slug')->toArray();

        $requestTerms = [];
        foreach ($requestItems as $item) {
            $term = Term::firstWhere('slug', $item);

            if ($term) {
                $requestTerms[] = $term->slug;

                if (! in_array($term->slug, $attachedTerms)) {
                    $origin->relatives()->attach($term, ['type' => $item['relation']]);

                    switch ($item['relation']) {
                        case 'variant':
                            $term->relatives()->attach($origin, ['type' => 'variant']);
                            break;
                        case 'reference':
                            $term->relatives()->attach($origin, ['type' => 'reference']);
                            break;
                        case 'component':
                            $term->relatives()->attach($origin, ['type' => 'descendant']);
                            break;
                        case 'descendant':
                            $term->relatives()->attach($origin, ['type' => 'component']);
                            break;
                    }
                }

            } else {
                $item && MissingTerm::create(['translit' => $item]);
            }
        }

        $detachableSlugs = array_diff($attachedTerms, $requestTerms);
        foreach ($detachableSlugs as $slug) {
            $term = Term::firstWhere('slug', $slug);
            $origin->relatives()->detach($term);

            if ($origin instanceof Term) {
                $term->relatives()->detach($origin);
            }
        }
    }

    private function handleDependents(
        object $term,
        array $requestDependents,
        string $dependentType,
        ?Collection $existingDependents = null,
        string $keyName = ''
    ): void {
        $requestItems = [];
        foreach ($requestDependents as $index => $dependent) {

            if ($existingDependents && ! $existingDependents->isEmpty()) {
                unset($dependent['id']);
                $requestItems[] = $dependent[$keyName];

                if ($index + 1 <= count($existingDependents)) {
                    $existingDependents[$index]->update($dependent);

                } else {
                    $newDependent = array_merge($dependent, ['term_id' => $term->id]);
                    $dependentType::create($newDependent);
                }
            } else {
                $dependent = array_merge($dependent, ['term_id' => $term->id]);
                $dependentType::create($dependent);
            }
        }

        $existingDependents = $existingDependents ?? collect([]);
        foreach ($existingDependents as $dependent) {
            if (! in_array($dependent->$keyName, $requestItems) && $keyName != '') {
                $dependent->delete();
            }
        }
    }

    public function edit(Term $term): \Illuminate\View\View
    {
        View::share('pageTitle', 'Edit Term');

        return view('terms.edit', [
            'term' => $term,
        ]);
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

        $this->flasher->addSuccess(__('deleted', ['thing' => $term->term]));

        return to_route('terms.index');
    }
}
