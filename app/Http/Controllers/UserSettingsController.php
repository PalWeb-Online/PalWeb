<?php

namespace App\Http\Controllers;

use App\Events\PasswordChanged;
use App\Events\ProfileChanged;
use App\Http\Requests\UpdatePasswordUserSettingRequest;
use App\Http\Requests\UpdateUserSettingRequest;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class UserSettingsController
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Renders the dashboard settings page
     */
    public function edit(Request $request): \Illuminate\View\View
    {
        View::share('pageTitle', 'Dashboard: Edit Profile');

        return view('users.dashboard.change-profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Stores changed information
     */
    public function update(UpdateUserSettingRequest $request, FlasherInterface $flasher)
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

    public function togglePrivacy(Request $request)
    {
        $user = $request->user();

        $user->private = ! $user->private;
        $user->private ? $status = 'Private' : $status = 'Public';
        $user->save();

        return [
            'isPrivate' => $user->private,
            'message' => __('privacy.updated', ['status' => $status]),
        ];
    }

    /**
     * Renders the change password page
     */
    public function editPassword(Request $request): \Illuminate\View\View
    {
        View::share('pageTitle', 'Dashboard: Edit Password');

        return view('users.dashboard.change-password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Updates the changed password
     */
    public function updatePassword(UpdatePasswordUserSettingRequest $request, FlasherInterface $flasher)
    {
        $user = $request->user();

        $form = $request->validated();

        $user->password = Hash::make($form['password_new']);
        $user->save();

        event(new PasswordChanged($user));

        $this->flasher->addSuccess(__('passwords.updated'));

        return to_route('users.show', $request->user());
    }

    /**
     * Renders the change picture page
     */
    public function editAvatar(Request $request)
    {
        $avatars = File::files(public_path('img/avatars'));
        $avatars = array_map(function ($file) {
            return basename($file);
        }, $avatars);

        View::share('pageTitle', 'Dashboard: Edit Profile Picture');

        return view('users.dashboard.change-avatar', [
            'user' => $request->user(),
            'avatars' => $avatars,
        ]);
    }

    /**
     * Updates the changed picture
     */
    public function updateAvatar(Request $request, FlasherInterface $flasher)
    {
        $user = $request->user();

        $user->avatar = $request['avatar'];
        $user->save();

        $flasher->addSuccess(__('settings.updated'));

        return to_route('users.show', $request->user());
    }
}
