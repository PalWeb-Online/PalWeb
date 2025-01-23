<?php

namespace App\Http\Controllers;

use App\Models\Dialog;
use App\Policies\TextPolicy;
use App\Traits\RedirectsToSubscribe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DialogController extends Controller
{
    use RedirectsToSubscribe;

    public function __construct(protected TextPolicy $can) {}

    public function index(Request $request): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Dialog Library');
        View::share('pageDescription',
            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewIndex($auth));

            return view('dialogs.index', [
                'dialogs' => Dialog::paginate(15)->onEachSide(1),
            ]);
        });
    }

    public function show(Request $request, Dialog $dialog): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        $dialog->load('sentences.terms');

        View::share('pageTitle', 'Dialog: '.$dialog->title);
        View::share('pageDescription',
            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $dialog) {
            $this->failIfFalse($this->can->viewText($auth));

            return view('dialogs.show', [
                'dialog' => $dialog,
                'bodyBackground' => 'yellow-pastel',
            ]);
        });
    }
}
