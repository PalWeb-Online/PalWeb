<?php

namespace App\Http\Controllers;

use App\Events\PasswordChanged;
use App\Events\ProfileChanged;
use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserSettingsController
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    /**
     * Renders the dashboard settings page
     */
    public function edit()
    {
        View::share('pageTitle', 'Dashboard: Edit Profile');

        return view('users.dashboard.change-profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Stores changed information
     */
    public function update(Request $request, FlasherInterface $flasher)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:50', new LatinScript()],
            'ar_name' => ['required', 'string', 'max:50', new ArabicScript()],
            'username' => [
                'required', 'string', 'regex:/^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/', 'max:50',
                Rule::unique('users')->ignore($user->id)
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

    public function togglePrivacy()
    {
        $user = auth()->user();

        $user->private = !$user->private;
        $user->private ? $status = 'Private' : $status = 'Public';
        $user->save();

        return [
            'isPrivate' => $user->private,
            'message' => __('privacy.updated', ['status' => $status])
        ];
    }

    /**
     * Renders the change password page
     */
    public function editPassword()
    {
        View::share('pageTitle', 'Dashboard: Edit Password');

        return view('users.dashboard.change-password', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Updates the changed password
     */
    public function updatePassword(Request $request, FlasherInterface $flasher)
    {
        $user = auth()->user();

        $form = $request->validate([
            'password_new' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->password = Hash::make($form['password_new']);
        $user->save();

        event(new PasswordChanged($user));

        $this->flasher->addSuccess(__('passwords.updated'));
        return to_route('users.show', auth()->user());
    }

    /**
     * Renders the change picture page
     */
    public function editAvatar()
    {
        $avatars = File::files(public_path('img/avatars'));
        $avatars = array_map(function ($file) {
            return basename($file);
        }, $avatars);

        View::share('pageTitle', 'Dashboard: Edit Profile Picture');

        return view('users.dashboard.change-avatar', [
            'user' => auth()->user(),
            'avatars' => $avatars
        ]);
    }

    /**
     * Updates the changed picture
     */
    public function updateAvatar(Request $request, FlasherInterface $flasher)
    {
        $user = auth()->user();

        $user->avatar = $request['avatar'];
        $user->save();

        $flasher->addSuccess(__('settings.updated'));
        return to_route('users.show', auth()->user());
    }
}
