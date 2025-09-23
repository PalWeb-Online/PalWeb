<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeckMasterController extends Controller
{
    public function __construct(private readonly QuizService $quizService)
    {
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
            $this->authorize('modify', $deck);
            $deck->load(['terms.pronunciations']);
        }

        return Inertia::render('Workbench/DeckMaster/Build', [
            'section' => 'workbench',
            'deck' => $deck ? new DeckResource($deck) : null,
        ]);
    }

    public function study(Deck $deck): \Inertia\Response|RedirectResponse
    {
        $deck->load(['terms', 'scores']);
        $isEmpty = $deck->terms->isEmpty();

        if ($isEmpty) {
            session()->flash('notification',
                [
                    'type' => 'warning',
                    'message' => __("You can't Quiz an empty Deck!")
                ]);

            return to_route('deck-master.index', ['mode' => 'study']);
        }

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
            'quiz' => $quiz
        ]);
    }

    public function getDecks(Request $request): JsonResponse
    {
        $mode = $request->query('mode', 'build');

        if ($mode === 'study') {
            $decks = Deck::whereHasBookmark($request->user())->with(['terms', 'scores'])->get();

        } else {
            $decks = $request->user()->decks->load(['terms']);
        }

        return response()->json([
            'decks' => DeckResource::collection($decks),
        ]);
    }

    public function getCards(Deck $deck): JsonResponse
    {
        $this->authorize('interact', $deck);

        return response()->json([
            'terms' => TermResource::collection(
                $deck->terms->load(['pronunciations'])->map(function ($term) {
                    return new TermResource($term)->additional(['detail' => true]);
                })
            )
        ]);
    }
}
