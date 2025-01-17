<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function show(User $user): \Illuminate\View\View | RedirectResponse
    {
        if (Gate::denies('interact', $user)) {
            $this->flasher->addFlash('error', __('unauthorized.private.user'), __('unauthorized'));

            return back();
        }

        $decks = $user->decks()->with('author')
            ->where(fn ($query) => $query->where('decks.private', false)
                ->orWhere('decks.user_id', auth()->user()->id)
            )
            ->get();

        View::share('pageTitle', $user->username);

        return view('users.show', [
            'user' => $user,
            'badges' => Badge::all(),
            'decks' => $decks,
            'bodyBackground' => 'hero-yellow',
        ]);
    }
}
