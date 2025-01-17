<?php

namespace App\Http\Controllers;

use App\Events\ProfileChanged;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function show(Request $request, User $user): \Illuminate\View\View | RedirectResponse
    {
        if (Gate::denies('interact', $user)) {
            $this->flasher->addFlash('error', __('unauthorized.private.user'), __('unauthorized'));

            return back();
        }

        $decks = $user->decks()->with('author')
            ->where(fn ($query) => $query->where('decks.private', false)
                ->orWhere('decks.user_id', $request->user()->id)
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

    public function edit(Request $request): \Illuminate\View\View
    {
        View::share('pageTitle', 'Settings: Change Profile');

        return view('users.dashboard.change-profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateUserRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'ar_name' => $request->ar_name,
            'username' => $request->username,
            'home' => $request->home,
            'bio' => $request->bio,
            'dialect_id' => $request->dialect,
        ]);

        event(new ProfileChanged($user));

        $this->flasher->addSuccess(__('settings.updated'));

        return to_route('users.show', $request->user());
    }
}
