<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Models\Deck;
use Illuminate\Support\Facades\View;

class DeckBuilderController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Deck Builder');

        return view('decks.builder', [
            'layout' => 'app',
        ]);
    }

    public function edit(Deck $deck): \Illuminate\View\View
    {
        $deck->load(['author', 'terms']);

        View::share('pageTitle', 'Deck Builder');

        return view('decks.builder', [
            'layout' => 'app',
            'stagedDeck' => new DeckResource($deck),
        ]);
    }
}
