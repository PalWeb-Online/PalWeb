<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeckMasterController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'workbench',
            'mode' => $request->query('mode', 'build'),
            'step' => 'select'
        ]);
    }

    public function build(?Deck $deck = null): \Inertia\Response
    {
        if ($deck) {
            $this->authorize('modify', $deck);
            $deck->load(['terms.pronunciations']);
        }

        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'workbench',
            'mode' => 'build',
            'step' => 'build',
            'deck' => $deck ? new DeckResource($deck) : null,
        ]);
    }

    public function study(Deck $deck): \Inertia\Response
    {
        $this->authorize('interact', $deck);

        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'academy',
            'mode' => 'study',
            'step' => 'study',
            'deck' => new DeckResource($deck),
            'terms' => TermResource::collection(
                $deck->terms->load(['pronunciations'])->map(function ($term) {
                    return new TermResource($term)->additional(['detail' => true]);
                })
            )
        ]);
    }

    public function getDecks(Request $request): \Illuminate\Http\JsonResponse
    {
        $mode = $request->query('mode', 'build');

        if ($mode === 'study') {
            $decks = Deck::whereHasBookmark($request->user())->with(['terms'])->get();
        } else {
            $decks = $request->user()->decks->load(['terms']);
        }

        return response()->json([
            'decks' => DeckResource::collection($decks),
        ]);
    }
}
