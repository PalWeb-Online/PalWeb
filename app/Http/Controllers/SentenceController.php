<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Models\Term;
use App\Services\SearchService;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maize\Markable\Models\Bookmark;

class SentenceController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function pin(Sentence $sentence)
    {
        $user = auth()->user();

        Bookmark::toggle($sentence, $user);

        $sentence->isPinned() && event(new ModelPinned($user));

        return response()->json([
            'isPinned' => $sentence->isPinned(),
            'message' => $sentence->isPinned()
                ? __('pin.added', ['thing' => $sentence->sentence])
                : __('pin.removed', ['thing' => $sentence->sentence])
        ]);
    }

    public function index(Request $request, SearchService $searchService)
    {
        View::share('pageTitle', 'the Phrasebook');
        View::share('pageDescription',
            'Discover the Phrasebook, a vast corpus of Palestinian Arabic within the PalWeb Dictionary. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        $searchTerm = $request->input('search');

        if ($searchTerm) {
            $allResults = $searchService->search($searchTerm, true, false)['sentences'];
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

        } else {
            $sentences = Sentence::orderByDesc('id')
                ->paginate(25)
                ->onEachSide(1);
            $totalCount = $sentences->total();
        }

        return view('sentences.index', compact('sentences', 'totalCount'));
    }

    public function show(Sentence $sentence)
    {
        View::share('pageTitle', 'Sentence: '.$sentence->translit);
        View::share('pageDescription',
            'Discover the Sentence Library, a vast corpus of Palestinian Arabic. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        return view('sentences.show', [
            'sentence' => $sentence
        ]);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $sentence = Sentence::create($this->buildSentence($request));

        $this->linkTerms($sentence, $request->terms);

        return [
            'status' => 'success',
            'redirect' => route('sentences.show', $sentence),
            'flash' => __('created', ['thing' => $sentence->sentence])
        ];
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'sentence.trans' => ['required'],
            'terms' => ['required', 'array'],
        ]);
    }

    public function create()
    {
        View::share('pageTitle', 'Create Sentence');
        return view('sentences.create');
    }

    private function buildSentence($request)
    {
        $terms = [];
        $translits = [];

        foreach ($request->terms as $term) {
            $terms[] = $term['sent_term'];
            $translits[] = $term['sent_translit'];
        }

        $sentence = $request->sentence;

        $sentence['sentence'] = implode(' ', $terms);
        $sentence['translit'] = implode(' ', $translits);

        return $sentence;
    }

    private function linkTerms($sentence, $terms)
    {
        DB::table('sentence_term')->where('sentence_id', $sentence->id)->delete();

        foreach ($terms as $termData) {
            DB::table('sentence_term')->insert([
                'sentence_id' => $sentence->id,
                'term_id' => $termData['term_id'] ?? null,
                'gloss_id' => $termData['gloss_id'] ?? null,
                'sent_term' => $termData['sent_term'],
                'sent_translit' => $termData['sent_translit'],
                'position' => $termData['position'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public
    function edit(
        Sentence $sentence
    ) {
        View::share('pageTitle', 'Edit Sentence');
        return view('sentences.edit', compact('sentence'));
    }

    public
    function update(
        Sentence $sentence,
        Request $request
    ) {
        $this->validateRequest($request);

        $sentence->update($this->buildSentence($request));

        $this->linkTerms($sentence, $request->terms);

        return [
            'status' => 'success',
            'redirect' => route('sentences.show', $sentence),
            'flash' => __('updated', ['thing' => $sentence->sentence])
        ];
    }

    public
    function destroy(
        Sentence $sentence
    ) {
        $sentence->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $sentence->sentence]));
        return to_route('sentences.index');
    }

    public
    function todo()
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

    public function get($id)
    {
        $sentence = Sentence::findOrFail($id);

        $terms = [];
        foreach ($sentence->allTerms() as $sentenceTerm) {
            $term = Term::find($sentenceTerm->id);

            if ($term) {
                $terms[] = [
                    'term' => [
                        'term' => $term->term,
                        'category' => $term->category,
                        'translit' => $term->translit,
                        'glosses' => $term->glosses->map(function ($gloss) {
                            return [
                                'id' => $gloss->id,
                                'gloss' => $gloss->gloss,
                            ];
                        })->toArray(),
                    ],
                    'term_id' => $term->id,
                    'gloss_id' => $sentenceTerm->gloss_id,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ];
            } else {
                $terms[] = [
                    'term' => [
                        'glosses' => []
                    ],
                    'term_id' => null,
                    'gloss_id' => null,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ];
            }
        }

        return response()->json([
            'sentence' => $sentence,
            'terms' => $terms
        ]);
    }
}
