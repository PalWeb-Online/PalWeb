<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreSentenceRequest;
use App\Http\Requests\UpdateSentenceRequest;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Models\Term;
use App\Services\SearchService;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maize\Markable\Models\Bookmark;

class SentenceController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function pin(Request $request, Sentence $sentence): JsonResponse
    {
        $user = $request->user();

        Bookmark::toggle($sentence, $user);

        $sentence->isPinned() && event(new ModelPinned($user));

        return response()->json([
            'isPinned' => $sentence->isPinned(),
            'message' => $sentence->isPinned()
                ? __('pin.added', ['thing' => $sentence->sentence])
                : __('pin.removed', ['thing' => $sentence->sentence]),
        ]);
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
            $sentences = Sentence::orderByDesc('id')
                ->paginate(25)
                ->onEachSide(1);
            $totalCount = $sentences->total();

        } else {
            $allResults = $searchService->search($filters, true, false)['sentences'];
            $totalCount = $allResults->count();

            $perPage = 25;
            $currentPage = $request->input('page', 1);
            $sentences = $allResults->forPage($currentPage, $perPage);

            $sentences = new \Illuminate\Pagination\LengthAwarePaginator(
                $sentences,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        View::share('pageTitle', 'Phrasebook');
        View::share('pageDescription',
            'Discover the Phrasebook, a vast corpus of Palestinian Arabic within the PalWeb Dictionary. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        return view('sentences.index', [
            'sentences' => $sentences,
            'filters' => $filters,
            'hasFilters' => $hasFilters,
            'totalCount' => $totalCount,
        ]);
    }

    public function show(Sentence $sentence): \Illuminate\View\View
    {
        View::share('pageTitle', 'Sentence: '.$sentence->translit);
        View::share('pageDescription',
            'Discover the Sentence Library, a vast corpus of Palestinian Arabic. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        return view('sentences.show', [
            'sentence' => $sentence,
        ]);
    }

    public function create(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Sentence Builder');

        return view('sentences.builder', [
            'layout' => 'app',
        ]);
    }

    public function edit($sentenceId): \Illuminate\View\View
    {
        $sentence = Sentence::findOrFail($sentenceId);

        $terms = [];
        foreach ($sentence->allTerms() as $sentenceTerm) {
            $term = Term::find($sentenceTerm->id);

            if ($term) {
                $terms[] = [
                    'id' => $term->id,
                    'term' => $term->term,
                    'category' => $term->category,
                    'translit' => $term->translit,
                    'glosses' => $term->glosses->map(function ($gloss) {
                        return [
                            'id' => $gloss->id,
                            'gloss' => $gloss->gloss,
                        ];
                    })->toArray(),
                    'pivot' => [
                        'gloss_id' => $sentenceTerm->gloss_id,
                        'sent_term' => $sentenceTerm->sent_term,
                        'sent_translit' => $sentenceTerm->sent_translit,
                        'position' => $sentenceTerm->position,
                    ]
                ];
            } else {
                $terms[] = [
                    'pivot' => [
                        'sent_term' => $sentenceTerm->sent_term,
                        'sent_translit' => $sentenceTerm->sent_translit,
                        'position' => $sentenceTerm->position,
                    ]
                ];
            }
        }

        $sentence->terms = $terms;

        View::share('pageTitle', 'Sentence Builder');

        return view('sentences.builder', [
            'layout' => 'app',
            'sentence' => $sentence,
            'action' => 'edit',
        ]);
    }

    public function store(StoreSentenceRequest $request): JsonResponse
    {
        $sentence = Sentence::create($this->buildSentence($request));

        $this->linkTerms($sentence, $request->sentence['terms']);

        return response()->json([
            'sentence' => $sentence,
        ]);
    }

    public function update(Sentence $sentence, UpdateSentenceRequest $request): JsonResponse
    {
        $sentence->update($this->buildSentence($request));

        $this->linkTerms($sentence, $request->sentence['terms']);

        return response()->json([
            'sentence' => $sentence,
        ]);
    }

    private function buildSentence($request): array
    {
        foreach ($request->sentence['terms'] as $term) {
            $terms[] = $term['pivot']['sent_term'];
            $translits[] = $term['pivot']['sent_translit'];
        }

        $sentence = $request->sentence;

        $sentence['sentence'] = implode(' ', $terms);
        $sentence['translit'] = implode(' ', $translits);

        unset($sentence['terms']);

        return $sentence;
    }

    private function linkTerms($sentence, $terms): void
    {
        DB::table('sentence_term')->where('sentence_id', $sentence->id)->delete();

        foreach ($terms as $termData) {
            DB::table('sentence_term')->insert([
                'sentence_id' => $sentence->id,
                'term_id' => $termData['id'] ?? null,
                'gloss_id' => $termData['pivot']['gloss_id'] ?? null,
                'sent_term' => $termData['pivot']['sent_term'],
                'sent_translit' => $termData['pivot']['sent_translit'],
                'position' => $termData['pivot']['position'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function destroy(Request $request, Sentence $sentence): RedirectResponse|JsonResponse
    {
        $sentence->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
            ]);

        } else {
            $this->flasher->addSuccess(__('deleted', ['thing' => $sentence->sentence]));

            return to_route('sentences.index');
        }
    }

    public function todo(): \Illuminate\View\View
    {
        $terms = [];

        Gloss::chunk(200, function ($glosses) use (&$terms) {
            foreach ($glosses as $gloss) {
                if (count($gloss->term->sentences($gloss->id)->get()) < 1) {
                    $gloss->term->gloss = $gloss->gloss;
                    $terms[] = $gloss->term;
                }

                if (count($terms) === 100) {
                    return false;
                }
            }
        });

        View::share('pageTitle', 'Phrasebook: to-Do');

        return view('sentences.todo', [
            'terms' => $terms,
        ]);
    }
}
