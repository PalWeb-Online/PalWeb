<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Services\QuizService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class DeckMasterController extends Controller
{
    public function __construct(
        protected TermService $termService,
        private readonly QuizService $quizService
    ) {
    }

    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Workbench/DeckMaster/Index', [
            'section' => 'workbench',
            'mode' => $request->query('mode', 'build'),
        ]);
    }



    public function fetchStudyDeck(Deck $deck): JsonResponse
    {
        $deck->load([
            'terms' => fn ($q) => $q
                ->whereNotNull('deck_term.gloss_id')
                ->withItemData()
                ->withUserCard(),
            'scores'
        ]);

        if ($deck->terms->isEmpty()) {
            return response()->json([
                'success' => false,
                'key' => 'pages.deck-master.notifications.empty-deck',
            ]);
        }

        $this->termService->hydratePronunciations($deck->terms);

        return response()->json([
            'success' => true,
            'deck' => new DeckResource($deck),
        ]);
    }

    public function getQuiz(Request $request, Deck $deck): JsonResponse
    {
        $settings = $request->input('settings', []);

        $quiz = $this->quizService->generateQuiz($deck, $settings);

        return response()->json([
            'quiz' => $quiz,
        ]);
    }

    public function getDecks(Request $request): JsonResponse
    {
        $user = $request->user();

        $decks = $request->query('mode') === 'study'
            ? Deck::query()
                ->whereHasBookmark($user)
                ->with([
                    'terms' => fn ($q) => $q
                        ->whereNotNull('deck_term.gloss_id')
                        ->withUserCard(),
                    'scores'
                ])
                ->get()
            : $user->decks()
                ->with([
                    'terms' => fn ($q) => $q->whereNotNull('deck_term.gloss_id'),
                ])
                ->get();

        return response()->json([
            'decks' => DeckResource::collection($decks),
        ]);
    }

    public function getCards(Deck $deck): JsonResponse
    {
        Gate::authorize('interact', $deck);

        $deck->load([
            'terms' => fn ($q) => $q
                ->whereNotNull('deck_term.gloss_id')
                ->withItemData()
                ->withUserCard()
                ->with('inflections'),
        ]);

        $this->termService->hydratePronunciations($deck->terms);

        return response()->json([
            'terms' => TermResource::collection($deck->terms),
        ]);
    }
}
