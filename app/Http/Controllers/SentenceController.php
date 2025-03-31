<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreSentenceRequest;
use App\Http\Requests\UpdateSentenceRequest;
use App\Http\Resources\SentenceResource;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Services\SearchService;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
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

    public function index(Request $request, SearchService $searchService): \Inertia\Response
    {
        $filters = $request->only(['search', 'match', 'pinned']);

        if (! collect($filters)->some(fn ($value) => ! empty($value))) {
            $sentences = Sentence::query()
                ->orderByDesc('id')
                ->paginate(25)
                ->onEachSide(1)
                ->appends($filters);
            $totalCount = $sentences->total();

        } else {
            $results = $searchService->search($filters, true, false)['sentences'];
            $totalCount = $results->count();

            $perPage = 25;
            $currentPage = $request->input('page', 1);
            $sentences = $results->forPage($currentPage, $perPage);

            $sentences = new \Illuminate\Pagination\LengthAwarePaginator(
                $sentences,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return Inertia::render('Library/Sentences/Index', [
            'section' => 'library',
            'sentences' => SentenceResource::collection(
                $sentences->getCollection()->map(function ($sentence) {
                    return new SentenceResource($sentence)->additional(['terms' => false]);
                })
            )->additional(['meta' => $sentences->toArray()]),
            'totalCount' => $totalCount,
            'filters' => $filters,
        ]);

//        View::share('pageDescription',
//            'Discover the Phrasebook, a vast corpus of Palestinian Arabic within the PalWeb Dictionary. Search and learn from real-life examples, seeing words in action for effective language mastery.');
    }

    public function show(Sentence $sentence): \Inertia\Response
    {
//        View::share('pageDescription',
//            'Discover the Sentence Library, a vast corpus of Palestinian Arabic. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        return Inertia::render('Library/Sentences/Show', [
            'section' => 'library',
            'sentence' => new SentenceResource($sentence)
        ]);
    }

    public function store(StoreSentenceRequest $request): RedirectResponse
    {
        $sentence = Sentence::create($this->buildSentence($request->all()));
        $this->linkTerms($sentence, $request->terms);

        return to_route('speech-maker.sentence', $sentence);
    }

    public function update(UpdateSentenceRequest $request, Sentence $sentence): RedirectResponse
    {
        $sentence->update($this->buildSentence($request->all()));
        $this->linkTerms($sentence, $request->terms);

        return to_route('speech-maker.sentence', $sentence);
    }

    private function buildSentence($sentenceData): array
    {
        foreach ($sentenceData['terms'] as $term) {
            $terms[] = $term['sentencePivot']['sent_term'];
            $translits[] = $term['sentencePivot']['sent_translit'];
        }

        $sentence = $sentenceData;

        $sentence['sentence'] = implode(' ', $terms);
        $sentence['translit'] = implode(' ', $translits);

        $dialog = $sentenceData['dialog'];
        $sentence['dialog_id'] = $dialog ? $dialog['id'] : null;

        return $sentence;
    }

    private function linkTerms($sentence, $terms): void
    {
        DB::table('sentence_term')->where('sentence_id', $sentence->id)->delete();

        foreach ($terms as $termData) {
            DB::table('sentence_term')->insert([
                'sentence_id' => $sentence->id,
                'term_id' => $termData['id'] ?? null,
                'gloss_id' => $termData['sentencePivot']['gloss_id'] ?? null,
                'sent_term' => $termData['sentencePivot']['sent_term'],
                'sent_translit' => $termData['sentencePivot']['sent_translit'],
                'position' => $termData['sentencePivot']['position'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function destroy(Sentence $sentence): RedirectResponse|JsonResponse
    {
        $sentence->delete();

        return to_route('sentences.index');
    }
}
