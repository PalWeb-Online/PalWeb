<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Policies\TextPolicy;
use App\Traits\RedirectsToSubscribe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;

/**
 * This class allows you to access Texts
 */
class TextController extends Controller
{
    use RedirectsToSubscribe;

    public function __construct(protected TextPolicy $can) {}

    public function index(): \Illuminate\View\View | RedirectResponse
    {
        $auth = auth()->user();

        View::share('pageTitle', 'Annotated Transcripts of Videos in Spoken Arabic');
        View::share('pageDescription',
            'Explore our collection of annotated transcripts for videos & dialogues in Spoken Arabic, complete with vocabulary decks & comprehension activities. Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewIndex($auth));

            return view('texts.index');
        });
    }

    public function show($slug): \Illuminate\View\View | RedirectResponse
    {
        $auth = auth()->user();

        View::share('pageTitle', 'Spoken Arabic Transcripts: '.$slug);
        View::share('pageDescription',
            'Explore our collection of annotated transcripts for videos & dialogues in Spoken Arabic, complete with vocabulary decks & comprehension activities. Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $slug) {
            $this->failIfFalse($this->can->viewText($auth));

            return view('texts.show', [
                'text' => $slug,
                'terms' => Term::all(),
                'bodyBackground' => 'yellow-pastel',
            ]);
        });
    }
}
