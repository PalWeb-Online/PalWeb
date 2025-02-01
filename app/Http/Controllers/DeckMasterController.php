<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Models\Deck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DeckMasterController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Deck Master');

        return view('decks.master', [
            'layout' => 'app',
        ]);
    }

    public function edit(Deck $deck): \Illuminate\View\View
    {
        $deck->load(['author', 'terms']);

        View::share('pageTitle', 'Deck Master: Edit Deck');

        return view('decks.master', [
            'layout' => 'app',
            'stagedDeck' => new DeckResource($deck),
        ]);
    }

    public function getPinnedDecks(Request $request): JsonResponse
    {
        $user = $request->user();

        $decks = Deck::with(['author', 'terms'])
            ->select('decks.*')
            ->where(fn ($query) => $query->where('decks.private', false)
                ->orWhere('decks.user_id', $user->id)
            )
            ->join('markable_bookmarks', fn ($join) => $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                ->where('markable_bookmarks.markable_type', '=', Deck::class)
                ->where('markable_bookmarks.user_id', '=', $user->id)
            )
            ->orderByDesc('markable_bookmarks.id')
            ->get();

        $pinnedDecks = DeckResource::collection($decks);

        return response()->json(['pinnedDecks' => $pinnedDecks]);
    }
}
