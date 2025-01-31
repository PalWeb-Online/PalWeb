<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Models\Deck;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CardViewerController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function index(): \Illuminate\View\View
    {
        $user = auth()->user();

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

        View::share('pageTitle', 'Card Viewer');

        return view('decks.viewer', [
            'layout' => 'app',
            'pinnedDecks' => $pinnedDecks
        ]);
    }

    public function getPinnedDecks(Request $request): JsonResponse
    {
        $user = $request->user();

        $decks = Deck::with('author')
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
