<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show(User $user)
    {
        if (Gate::denies('interact', $user)) {
            $this->flasher->addFlash('error', __('unauthorized.private.user'), __('unauthorized'));
            return back();
        }

        if (auth()->user()->id === $user->id) {
            $decks = $user->decks
                ->load('author');
        } else {
            $decks = $user->decks
                ->where('private', 0)
                ->load('author');
        }

        View::share('pageTitle', $user->username);

        return view('users.show', [
            'user' => $user,
            'badges' => Badge::all(),
            'decks' => $decks,
            'bodyBackground' => 'hero-yellow'
        ]);
    }
}
