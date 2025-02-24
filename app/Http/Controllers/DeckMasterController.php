<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Models\Deck;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeckMasterController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Workbench/DeckMaster', [
            'section' => 'workbench',
            'mode' => $request->query('mode', 'build'),
            'step' => 'select'
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        return Inertia::render('Workbench/DeckMaster', [
            'section' => 'workbench',
            'mode' => 'build',
            'step' => 'build'
        ]);
    }

    public function edit(Request $request, Deck $deck): \Inertia\Response
    {
        $this->authorize('modify', $deck);
        $deck->load(['author', 'terms']);

        return Inertia::render('Workbench/DeckMaster', [
            'section' => 'workbench',
            'mode' => 'build',
            'step' => 'build',
            'stagedDeck' => new DeckResource($deck),
        ]);
    }

    public function study(Request $request, Deck $deck): \Inertia\Response
    {
        $this->authorize('interact', $deck);
        $deck->load(['author', 'terms']);

        return Inertia::render('Workbench/DeckMaster', [
            'section' => 'academy',
            'mode' => 'study',
            'step' => 'study',
            'stagedDeck' => new DeckResource($deck),
        ]);
    }
}
