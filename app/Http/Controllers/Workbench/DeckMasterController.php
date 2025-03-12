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

    public function getDecks(Request $request)
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


    public function create(Request $request): \Inertia\Response
    {
        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'workbench',
            'mode' => 'build',
            'step' => 'build'
        ]);
    }

    public function edit(Request $request, Deck $deck): \Inertia\Response
    {
        $this->authorize('modify', $deck);
        $deck->load(['terms']);

        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'workbench',
            'mode' => 'build',
            'step' => 'build',
            'stagedDeck' => new DeckResource($deck),
        ]);
    }

    public function study(Request $request, Deck $deck): \Inertia\Response
    {
        $this->authorize('interact', $deck);
        $deck->load(['terms']);

        return Inertia::render('Workbench/DeckMaster/DeckMaster', [
            'section' => 'academy',
            'mode' => 'study',
            'step' => 'study',
            'stagedDeck' => new DeckResource($deck),
            'deckCards' => TermResource::collection(
                $deck->terms->map(function ($term) {
                    return new TermResource($term)->additional(['detail' => true]);
                })
            )
        ]);
    }
}
