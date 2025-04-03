<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;

class ExploreController extends Controller
{
    public function index(): \Illuminate\View\View|RedirectResponse
    {
        View::share('pageTitle', 'Explore Topics in Palestinian Arabic');
        View::share('pageDescription',
            'Explore Palestinian culture and language through themed pages on food, health, nature, and more. Dive into vocabulary decks and dialogues that will greatly enrich your learning journey.');

        return view('explore.index');
    }

    public function show($page): \Illuminate\View\View|RedirectResponse
    {
        View::share('pageTitle', 'Explore: '.ucwords($page));
        View::share('pageDescription',
            'Explore Palestinian culture and language through themed pages on food, health, nature, and more. Dive into vocabulary decks and dialogues that will greatly enrich your learning journey.');

        return view('explore.show', [
            'page' => $page,
            'terms' => Term::all(),
        ]);
    }
}
