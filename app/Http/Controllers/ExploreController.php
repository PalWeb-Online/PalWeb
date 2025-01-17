<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Policies\ExplorePolicy;
use App\Traits\RedirectsToSubscribe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ExploreController extends Controller
{
    use RedirectsToSubscribe;

    public function __construct(protected ExplorePolicy $can) {}

    public function index(Request $request): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Explore Topics in Palestinian Arabic');
        View::share('pageDescription',
            'Explore Palestinian culture and language through themed pages on food, health, nature, and more. Dive into vocabulary decks and dialogues that will greatly enrich your learning journey.');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewIndex($auth));

            return view('explore.index');
        });
    }

    public function show(Request $request, $page): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Explore: '.ucwords($page));
        View::share('pageDescription',
            'Explore Palestinian culture and language through themed pages on food, health, nature, and more. Dive into vocabulary decks and dialogues that will greatly enrich your learning journey.');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $page) {
            $this->failIfFalse($this->can->viewExplore($auth));

            return view('explore.show', [
                'page' => $page,
                'terms' => Term::all(),
            ]);
        });
    }
}
