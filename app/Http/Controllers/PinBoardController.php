<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

class PinBoardController
{
    public function index(Request $request): \Inertia\Response
    {
        $terms = Term::query()
            ->whereHasBookmark($request->user())
            ->paginate(20)
            ->appends(['mode' => $request->query('mode', 'terms')]);

        $sentences = Sentence::query()
            ->whereHasBookmark($request->user())
            ->paginate(20)
            ->appends(['mode' => $request->query('mode', 'sentences')]);

        return Inertia::render('Workbench/PinBoard', [
            'section' => 'workbench',
            'mode' => $request->query('mode', 'terms'),
            'terms' => TermResource::collection($terms),
            'sentences' => SentenceResource::collection($sentences),
        ]);
    }
}

// Somehow terms are sorted by when they were pinned, but decks & sentences are sorted by the model's id.

//        $decks = Deck::with(['author', 'terms'])
//            ->select('decks.*')
//            ->where(fn ($query) => $query->where('decks.private', false)
//                ->orWhere('decks.user_id', $request->user()->id)
//            )
//            ->join('markable_bookmarks', fn ($join) => $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
//                ->where('markable_bookmarks.markable_type', '=', Deck::class)
//                ->where('markable_bookmarks.user_id', '=', $request->user()->id)
//            )
//            ->orderBy('markable_bookmarks.id')
//            ->get();
//
//        $terms = Term::with(['glosses'])
//            ->select('terms.*')
//            ->join('markable_bookmarks', fn ($join) => $join->on('terms.id', '=', 'markable_bookmarks.markable_id')
//                ->where('markable_bookmarks.markable_type', '=', Term::class)
//                ->where('markable_bookmarks.user_id', '=', $request->user()->id)
//            )
//            ->orderBy('markable_bookmarks.id')
//            ->get();
//
//        $sentences = Sentence::select('sentences.*')
//            ->join('markable_bookmarks', fn ($join) => $join->on('sentences.id', '=', 'markable_bookmarks.markable_id')
//                ->where('markable_bookmarks.markable_type', '=', Sentence::class)
//                ->where('markable_bookmarks.user_id', '=', $request->user()->id)
//            )
//            ->orderBy('markable_bookmarks.id')
//            ->get();

//        if ($request->input('sort') == 'newest') {
//            $decks = $decks->reverse();
//            $terms = $terms->reverse();
//            $sentences = $sentences->reverse();
//        }
