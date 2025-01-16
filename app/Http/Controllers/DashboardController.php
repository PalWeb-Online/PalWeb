<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController
{
    public function workbench(Request $request): \Illuminate\View\View
    {
        // TODO: Somehow terms are sorted by when they were pinned, but decks & sentences are sorted by the model's id.
        $decks = Deck::whereHasBookmark(auth()->user())->get();
        $terms = Term::whereHasBookmark(auth()->user())->get();
        $sentences = Sentence::whereHasBookmark(auth()->user())->get();

        $decks = Deck::with('author')
            ->select('decks.*')
            ->where(fn ($query) => $query->where('decks.private', false)
                ->orWhere('decks.user_id', auth()->user()->id)
            )
            ->join('markable_bookmarks', fn ($join) => $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                ->where('markable_bookmarks.markable_type', '=', Deck::class)
                ->where('markable_bookmarks.user_id', '=', auth()->user()->id)
            )
            ->orderBy('markable_bookmarks.id')
            ->get();

        $terms = Term::select('terms.*')
            ->join('markable_bookmarks', fn ($join) => $join->on('terms.id', '=', 'markable_bookmarks.markable_id')
                ->where('markable_bookmarks.markable_type', '=', Term::class)
                ->where('markable_bookmarks.user_id', '=', auth()->user()->id)
            )
            ->orderBy('markable_bookmarks.id')
            ->get();

        $sentences = Sentence::select('sentences.*')
            ->join('markable_bookmarks', fn ($join) => $join->on('sentences.id', '=', 'markable_bookmarks.markable_id')
                ->where('markable_bookmarks.markable_type', '=', Sentence::class)
                ->where('markable_bookmarks.user_id', '=', auth()->user()->id)
            )
            ->orderBy('markable_bookmarks.id')
            ->get();

        if ($request->input('sort') == 'newest') {
            $decks = $decks->reverse();
            $terms = $terms->reverse();
            $sentences = $sentences->reverse();
        }

        View::share('pageTitle', 'Dashboard: Workbench');

        return view('users.dashboard.workbench', [
            'decks' => $decks,
            'terms' => $terms,
            'sentences' => $sentences,
            'bodyBackground' => 'hero-yellow',
        ]);
    }

    public function subscription(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Dashboard: Subscription');

        return view('users.dashboard.subscription', [
            'user' => auth()->user(),
            'bodyBackground' => 'hero-yellow',
        ]);
    }
}
