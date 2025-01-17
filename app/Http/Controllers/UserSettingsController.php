<?php

namespace App\Http\Controllers;

use App\Events\PasswordChanged;
use App\Events\ProfileChanged;
use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserSettingsController
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function edit(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Settings: Change Profile');

        return view('users.dashboard.change-profile', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:50', new LatinScript],
            'ar_name' => ['required', 'string', 'max:50', new ArabicScript],
            'username' => [
                'required', 'string', 'max:50',
                'regex:/^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'home' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

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

        return to_route('users.show', auth()->user());
    }

    public function togglePrivacy(): JsonResponse
    {
        $user = auth()->user();

        $user->private = ! $user->private;
        $user->private ? $status = 'Private' : $status = 'Public';
        $user->save();

        return response()->json([
            'isPrivate' => $user->private,
            'message' => __('privacy.updated', ['status' => $status]),
        ]);
    }
}
