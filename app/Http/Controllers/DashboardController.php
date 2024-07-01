<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController
{
    public function workbench(Request $request)
    {
        $user = auth()->user();

        // TODO: Somehow terms are sorted by when they were pinned, but decks & sentences are sorted by the model's id.
        $decks = Deck::whereHasBookmark(auth()->user())->get();
        $terms = Term::whereHasBookmark(auth()->user())->get();
        $sentences = Sentence::whereHasBookmark(auth()->user())->get();

        if ($request->input('sort') == 'newest') {
            $decks = $decks->reverse();
            $terms = $terms->reverse();
            $sentences = $sentences->reverse();
        }

        View::share('pageTitle', 'Dashboard: Workbench');

        return view('users.dashboard.workbench', [
            'user' => $user,
            'decks' => $decks,
            'terms' => $terms,
            'sentences' => $sentences,
        ]);
    }

    public function subscription()
    {
        View::share('pageTitle', 'Dashboard: Subscription');

        return view('users.dashboard.subscription', [
            'user' => auth()->user(),
        ]);
    }
}
