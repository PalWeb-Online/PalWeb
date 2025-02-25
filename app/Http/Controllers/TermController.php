<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Models\Attribute;
use App\Models\Dialect;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maize\Markable\Models\Bookmark;

class TermController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
        protected TermRepository $termRepository
    ) {}

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

    public function loadPronunciations(Collection $terms, Request $request): Collection
    {
        $terms->each(function ($term) {
            $term->load(['pronunciations.audios.speaker']);
        });

        $allAudios = $request->routeIs('terms.audios');

        foreach ($terms as $term) {
            $term->pronunciations->each(function ($pronunciation) use ($allAudios) {
                $pronunciation->audio_count = $pronunciation->audios->count();

                if (! $allAudios) {
                    $pronunciation->audios = $pronunciation->audios->take(1);
                }
            });

            if ($request->user()) {
                $dialect = $request->user()->dialect_id;
                $dialects = Dialect::find($dialect)->ancestors->pluck('id')
                    ->merge(Dialect::find($dialect)->descendants->pluck('id'))
                    ->push($dialect);
                $term->userPronunciations = $term->pronunciations->whereIn('dialect_id', $dialects);
                $term->otherPronunciations = $term->pronunciations->whereNotIn('dialect_id', $dialects);
            }
        }

        return $terms;
    }

    public function index(Request $request, SearchService $searchService): \Illuminate\View\View
    {
        $filters = array_merge([
            'search' => '',
            'category' => '',
            'attribute' => '',
            'form' => '',
            'singular' => '',
            'plural' => '',
        ], $request->only(['search', 'category', 'attribute', 'form', 'singular', 'plural']));
        $filters = array_map(fn($value) => $value ?? '', $filters);

        $hasFilters = collect($filters)->some(fn($value) => !empty($value));

        if (!$hasFilters) {
            $terms = Term::orderByDesc('id')
                ->paginate(100)
                ->onEachSide(1);
            $totalCount = $terms->total();

        } else {
            $allResults = $searchService->search($filters)['terms'];
            $totalCount = $allResults->count();

            $perPage = 100;
            $currentPage = $request->input('page', 1);
            $terms = $allResults->forPage($currentPage, $perPage);

            $terms = new \Illuminate\Pagination\LengthAwarePaginator(
                $terms,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        if (! $request->query()) {
            $latestTerms = Term::with('glosses')->orderByDesc('id')->take(7)->get();
            $wordOfTheDay = Cache::get('word-of-the-day');

            if (! $wordOfTheDay) {
                $wordOfTheDay = Term::whereNotNull('image')->inRandomOrder()->first();
            }
        }

        View::share('pageTitle', 'the Dictionary');
        View::share('pageDescription',
            'Discover the PalWeb Dictionary, an extensive, practical & fun-to-use online dictionary for Levantine Arabic, complete with pronunciation audios & example sentences. Boost your Palestinian Arabic vocabulary now!');

        return view('terms.index', [
            'terms' => $terms,
            'filters' => $filters,
            'hasFilters' => $hasFilters,
            'totalCount' => $totalCount,
            'wordOfTheDay' => $wordOfTheDay ?? null,
            'latestTerms' => $latestTerms ?? null,
        ]);
    }

    public function get($id): JsonResponse
    {
        $term = Term::with([
            'root',
            'patterns',
            'attributes',
            'pronunciations',
            'relatives',
            'spellings',
            'inflections',
            'glosses' => function ($query) {
                $query->with([
                    'attributes',
                    'relatives',
                ]);
            },
        ])->findOrFail($id);

        return response()->json([
            'term' => $term,
            'root' => $term->root->root ?? '',
            'singPatterns' => $term->patterns->where('type', 'singular')->values(),
            'plurPatterns' => $term->patterns->where('type', 'plural')->values(),
            'verbPatterns' => $term->patterns->where('type', 'verbal')->values(),
            'pronunciations' => $term->pronunciations,
            'variants' => $term->variants->map(function ($relative) {
                return [
                    'slug' => $relative->slug,
                    'relation' => $relative->pivot->type,
                ];
            }),
            'references' => $term->references->map(function ($relative) {
                return [
                    'slug' => $relative->slug,
                    'relation' => $relative->pivot->type,
                ];
            }),
            'components' => $term->components->map(function ($relative) {
                return [
                    'slug' => $relative->slug,
                    'relation' => $relative->pivot->type,
                ];
            }),
            'descendants' => $term->descendants->map(function ($relative) {
                return [
                    'slug' => $relative->slug,
                    'relation' => $relative->pivot->type,
                ];
            }),
            'spellings' => $term->spellings,
            'inflections' => $term->inflections,
            'glosses' => $term->glosses->map(function ($gloss) {
                return [
                    'id' => $gloss->id,
                    'gloss' => $gloss->gloss,
                    'attributes' => $gloss->attributes,
                    'relatives' => $gloss->relatives->map(function ($relative) {
                        return [
                            'slug' => $relative->slug,
                            'relation' => $relative->pivot->type,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function show(Term $term, Request $request): \Illuminate\View\View
    {
        if ($request->routeIs('terms.show')) {
            $likeTerms = $this->termRepository->getLikeTerms($term);
            $terms = collect([$term, ...$likeTerms->duplicates, ...$likeTerms->homophones])->filter();

        } else {
            $terms = collect([$term]);
        }

        $terms = $this->loadPronunciations($terms, $request);

        $attributeOrder = ['masculine', 'feminine', 'plural', 'collective', 'demonym', 'clitic', 'idiom'];
        $sortedAttributes = $term->attributes->sortBy(function ($attr) use ($attributeOrder) {
            return array_search($attr->attribute, $attributeOrder);
        });
        $term->attributes = $sortedAttributes->values();

        View::share('pageTitle', 'Term: '.$term->term.' ('.$term->translit.')');
        View::share('pageDescription',
            'Discover an extensive, practical & fun-to-use online dictionary for Levantine Arabic, complete with pronunciation audios & example sentences. Boost your Palestinian Arabic vocabulary now!');

        return view('terms.show', [
            'terms' => $terms,
        ]);
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
    ): void
    {
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
