<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
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
use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Maize\Markable\Models\Bookmark;

class TermController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
        protected TermRepository $termRepository
    ) {
    }

    public function pin(Term $term)
    {
        $user = auth()->user();

        Bookmark::toggle($term, $user);

        $term->isPinned() && event(new ModelPinned($user));

        return response()->json([
            'isPinned' => $term->isPinned(),
            'message' => $term->isPinned()
                ? __('pin.added', ['thing' => $term->term])
                : __('pin.removed', ['thing' => $term->term])
        ]);
    }

    public function usages(Term $term)
    {
        $terms = [];
        $terms[] = $term;

        return view('terms.show', [
            'terms' => $terms
        ]);
    }

    /**
     * Loads the Dictionary Index
     */
    public function index()
    {
        $defaultFilters = [
            'category' => '',
            'attribute' => '',
            'form' => '',
            'singular' => '',
            'plural' => '',
            'search' => ''
        ];

        $filter = array_merge($defaultFilters, request()->only(array_keys($defaultFilters)));
        $terms = $this->termRepository->getTerms($filter);

        $terms->appends($filter);

        $totalCount = $terms->total();


        if (!(bool) request()->query()) {
            $latestTerms = Term::with('glosses')->orderBy('id', 'desc')->take(7)->get();
            $wordOfTheDay = Cache::get('word-of-the-day');

            if (!$wordOfTheDay) {
                $wordOfTheDay = Term::whereNotNull('image')->inRandomOrder()->first();
            }

        } else {
            $latestTerms = null;
            $wordOfTheDay = null;
        }

        View::share('pageTitle', 'the Dictionary');
        View::share('pageDescription',
            'Discover the PalWeb Dictionary, an extensive, practical & fun-to-use online dictionary for Levantine Arabic, complete with pronunciation audios & example sentences. Boost your Palestinian Arabic vocabulary now!');

        return view('terms.index', [
            'terms' => $terms,
            'filter' => $filter,
            'wordOfTheDay' => $wordOfTheDay,
            'latestTerms' => $latestTerms,
            'totalCount' => $totalCount
        ]);
    }

    /**
     * Retrieves a Term for updating
     */
    public function get($id)
    {
        $term = Term::with([
            'root',
            'patterns',
            'attributes',
            'pronunciations',
            'variants',
            'components',
            'references',
            'spellings',
            'inflections',
            'glosses' => function ($query) {
                $query->with([
                    'attributes',
                    'synonyms',
                    'antonyms',
                    'valences',
                ]);
            }
        ])->findOrFail($id);

        return [
            'term' => $term,
            'root' => $term->root->root ?? '',
            'singPatterns' => $term->patterns->where('type', 'singular')->values(),
            'plurPatterns' => $term->patterns->where('type', 'plural')->values(),
            'verbPatterns' => $term->patterns->where('type', 'verbal')->values(),
            'pronunciations' => $term->pronunciations,
            'variants' => $term->variants->pluck('slug'),
            'components' => $term->components->pluck('slug'),
            'references' => $term->references->pluck('slug'),
            'spellings' => $term->spellings,
            'inflections' => $term->inflections,
            'glosses' => $term->glosses->map(function ($gloss) {
                return [
                    'id' => $gloss->id,
                    'gloss' => $gloss->gloss,
                    'attributes' => $gloss->attributes,
                    'synonyms' => $gloss->synonyms->pluck('slug'),
                    'antonyms' => $gloss->antonyms->pluck('slug'),
                    'valences' => $gloss->valences->map(function ($valence) {
                        return [
                            'slug' => $valence->slug,
                            'relation' => $valence->pivot->type,
                        ];
                    }),
                ];
            }),
        ];
    }

    public function search(Request $request)
    {
        if ($request->searchTerm == null) {
            $searchResults = [];
            return response()->json(compact('searchResults'));
        }

        $search = $request->searchTerm;

        $results = Term::query()
            ->select('terms.*')
            ->leftJoin('roots', 'terms.root_id', '=', 'roots.id')
            ->filter(['search' => $search])
            ->orderByRaw('COALESCE(roots.root, terms.term) ASC')
            ->orderBy('terms.term', 'ASC')
            ->with('glosses')
            ->take(10)
            ->get();

        $searchResults = $results->map(function ($term) {
            return [
                'id' => $term->id,
                'term' => $term->term,
                'slug' => $term->slug,
                'category' => $term->category,
                'translit' => $term->translit,
                'glosses' => $term->glosses->map(function ($gloss) {
                    return [
                        'id' => $gloss->id,
                        'gloss' => $gloss->gloss,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return response()->json(compact('searchResults'));
    }

    public function show(Term $term, Request $request)
    {
        Log::info('Term Page:', [
            'url' => $request->fullUrl(),
        ]);

        $allPronunciations = $term->pronunciations;
        $userPronunciations = collect();
        $otherPronunciations = collect();

        if (auth()->check()) {
            $dialect = auth()->user()->dialect_id;
            $dialects = Dialect::find($dialect)->ancestors->pluck('id')
                ->merge(Dialect::find($dialect)->descendants->pluck('id'))
                ->push($dialect);
            $userPronunciations = $allPronunciations->whereIn('dialect_id', $dialects);
            $otherPronunciations = $allPronunciations->whereNotIn('dialect_id', $dialects);
        }

        $likeTerms = $this->termRepository->getLikeTerms($term);
        $terms = collect([$term, ...$likeTerms->duplicates, ...$likeTerms->homophones])->filter();

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
            'allPronunciations' => $allPronunciations,
            'userPronunciations' => $userPronunciations,
            'otherPronunciations' => $otherPronunciations,
        ]);
    }

    public function random()
    {
        $term = Term::inRandomOrder()->first();
        if (!$term) {
            return to_route('terms.index');
        }
        return to_route('terms.show', $term);
    }

    /**
     * Creates a Term
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

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

            $attributes = array_map(fn($item) => $item['attribute'], $request->term['attributes']);
            foreach ($attributes as $attribute) {
                Attribute::firstWhere('attribute', $attribute)->terms()->attach($term);
            }

            $this->handlePatterns($term, $request->singPatterns, 'singular');
            $this->handlePatterns($term, $request->plurPatterns, 'plural');
            $this->handlePatterns($term, $request->verbPatterns, 'verbal');

            $this->handleRelatives($term, $request->variants, 'variants', 'variant');
            $this->handleRelatives($term, $request->references, 'references', 'reference');
            $this->handleRelatives($term, $request->components, 'components', 'descendant');

            $this->handleDependents($term, $request->spellings, Spelling::class);
            $this->handleDependents($term, $request->inflections, Inflection::class);
            $this->handleDependents($term, $request->pronunciations, Pronunciation::class);

            foreach ($request->glosses as $requestGloss) {
                $requestGloss = array_merge($requestGloss, ['term_id' => $term->id]);
                $gloss = Gloss::create($requestGloss);

                $glossAttributes = array_map(fn($item) => $item['attribute'], $requestGloss['attributes']);
                foreach ($glossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->attach($gloss);
                }

                $this->handleRelatives($gloss, $requestGloss['synonyms'], 'synonyms');
                $this->handleRelatives($gloss, $requestGloss['antonyms'], 'antonyms');
                $this->handleRelatives($gloss, $requestGloss['valences'], 'valences');
            }

            return $term;
        });

        return [
            'status' => 'success',
            'term' => $term,
            'redirect' => route('terms.show', $term),
            'flash' => __('created', ['thing' => $term->term])
        ];
    }

    private function validateRequest($request): void
    {
        $request->validate([
            'term.term' => ['required', new ArabicScript()],
            'root' => ['nullable', 'min:3', 'max:4', new ArabicScript()],
            'inflections.*.inflection' => ['required', new ArabicScript()],
            'inflections.*.translit' => ['required', new LatinScript()],
            'spellings.*.spelling' => ['required', new ArabicScript()],
            'variants.*' => ['required', new LatinScript()],
            'references.*' => ['required', new LatinScript()],
            'components.*' => ['required', new LatinScript()],
            'glosses.*.synonyms.*' => ['required', new LatinScript()],
            'glosses.*.antonyms.*' => ['required', new LatinScript()],
        ]);
    }

    public function handleSlug($category, $translit, Term $term = null)
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

    /**
     * Updates a Term
     */
    public function update(Term $term, Request $request)
    {
        $this->validateRequest($request);

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

            $requestAttributes = array_map(fn($item) => $item['attribute'], $request->term['attributes']);
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

            $this->handleRelatives($term, $request->variants, 'variants', 'variant');
            $this->handleRelatives($term, $request->references, 'references', 'reference');
            $this->handleRelatives($term, $request->components, 'components', 'descendant');

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

                $requestGlossAttributes = array_map(fn($item) => $item['attribute'], $requestGloss['attributes']);
                foreach ($requestGlossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->syncWithoutDetaching($gloss->id);
                }

                $detachableGlossAttributes = array_diff($gloss->attributes->pluck('attribute')->toArray(), $requestGlossAttributes);
                foreach ($detachableGlossAttributes as $attribute) {
                    Attribute::firstWhere('attribute', $attribute)->glosses()->detach($gloss);
                }

                $this->handleRelatives($gloss, $requestGloss['synonyms'], 'synonyms');
                $this->handleRelatives($gloss, $requestGloss['antonyms'], 'antonyms');
                $this->handleRelatives($gloss, $requestGloss['valences'], 'valences');
            }

            foreach ($term->glosses as $gloss) {
                !in_array($gloss->gloss, $requestGlosses) && $gloss->delete();
            }

            Root::doesntHave('terms')->delete();

            return $term;
        });

        return [
            'status' => 'success',
            'term' => $term,
            'redirect' => route('terms.show', $term),
            'flash' => __('updated', ['thing' => $term->term])
        ];
    }

    private function handlePatterns($term, $requestPatterns, $type)
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

    private
    function handleRelatives(
        object $origin,
        array $requestItems,
        string $relation,
        string $reverseRelation = null
    ) {
        $attachedTerms = $origin->$relation->pluck('slug')->toArray();
        $requestTerms = [];

        foreach ($requestItems as $item) {
            $term = Term::firstWhere('slug', $item);
            $type = is_array($item) ? $item['relation'] : $relation;

            if ($term) {
                $requestTerms[] = $term->slug;

                if (!in_array($term->slug, $attachedTerms)) {
                    $origin->$relation()->attach($term, ['type' => Str::singular($type)]);
                    $reverseRelation && $term->$relation()->attach($origin, ['type' => $reverseRelation]);
                }

            } else {
                $item && MissingTerm::create(['translit' => $item]);
            }
        }

        $detachableSlugs = array_diff($attachedTerms, $requestTerms);
        foreach ($detachableSlugs as $slug) {
            $origin->$relation()->detach(Term::firstWhere('slug', $slug));
        }
    }

    /**
     * Loads the Term Creator
     */
    public
    function create()
    {
        View::share('pageTitle', 'Create Term');
        return view('terms.create');
    }

    private
    function handleDependents(
        object $term,
        array $requestDependents,
        string $dependentType,
        Collection $existingDependents = null,
        string $keyName = ''
    ) {
        $requestItems = [];
        foreach ($requestDependents as $index => $dependent) {

            if ($existingDependents && !$existingDependents->isEmpty()) {
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
            if (!in_array($dependent->$keyName, $requestItems) && $keyName != '') {
                $dependent->delete();
            }
        }
    }

    /**
     * Loads the Term Editor
     */
    public function edit(Term $term)
    {
        View::share('pageTitle', 'Edit Term');
        return view('terms.edit', [
            'term' => $term
        ]);
    }

    /**
     * Deletes a Term
     */
    public
    function destroy(
        Term $term
    ) {
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

    public
    function request(
        Request $request
    ) {
        $request->validate([
            'translit' => 'required|string|max:255',
        ]);

        MissingTerm::create([
            'translit' => $request['translit'],
            'category' => $request['category']
        ]);

        $this->flasher->addSuccess(__('terms.requested'));
        return to_route('terms.index');
    }

    public function todo()
    {
        $missingInflections = [];
        foreach (Inflection::whereIn('form', ['ap', 'pp', 'nv'])->get() as $inflection) {
            if (Term::where('translit', $inflection->translit)->doesntExist()) {
                $missingInflections[] = $inflection;
            }
        }
        $missingInflections = collect($missingInflections);

        View::share('pageTitle', 'Dictionary: to-Do');
        return view('terms.todo', [
            'fromSentences' => DB::table('sentence_term')->whereNull('term_id')->get(),
            'missingTerms' => MissingTerm::all(),
            'missingInflections' => $missingInflections,
        ]);
    }
}
