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

    public function build(?Deck $deck = null): \Inertia\Response
    {
        if ($deck) {
            Gate::authorize('modify', $deck);

            $deck->load([
                'terms' => fn ($q) => $q
                    ->withItemData()
            ]);

            $this->termService->hydratePronunciations($deck->terms);
        }

        return Inertia::render('Workbench/DeckMaster/Build', [
            'section' => 'workbench',
            'deck' => $deck ? new DeckResource($deck) : null,
        ]);
    }

    public function study(Deck $deck): \Inertia\Response|RedirectResponse
    {
        $deck->load([
            'terms' => fn ($q) => $q
                ->withItemData()
                ->withUserCard(),
            'scores'
        ]);

        if ($deck->terms->isEmpty()) {
            session()->flash('notification',
                [
                    'type' => 'warning',
                    'message' => __("You can't Quiz an empty Deck!"),
                ]);

            return to_route('deck-master.index', ['mode' => 'study']);
        }

        $this->termService->hydratePronunciations($deck->terms);

        return Inertia::render('Workbench/DeckMaster/Study', [
            'section' => 'academy',
            'deck' => new DeckResource($deck),
        ]);
    }

    public function getQuiz(Request $request, Deck $deck): JsonResponse
    {
        $settings = $request->get('settings', []);

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
                        ->withUserCard(),
                    'scores'
                ])
                ->get()
            : $user->decks()
                ->with(['terms'])
                ->get();

        return response()->json([
            'decks' => DeckResource::collection($decks),
        ]);
    }

    public function getCards(Deck $deck): JsonResponse
    {
        Gate::authorize('interact', $deck);

        $terms = $deck->terms->load([
            'pronunciations',
            'glosses',
            'cards',
            'inflections'
        ]);

        return response()->json([
            'terms' => TermResource::collection($terms),
        ]);
    }
}
