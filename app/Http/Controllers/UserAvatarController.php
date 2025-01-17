<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class UserAvatarController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function edit(Request $request): \Illuminate\View\View
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

    public function update(Request $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = $request->user();

        $user->avatar = $request['avatar'];
        $user->save();

        $flasher->addSuccess(__('settings.updated'));

        return to_route('users.show', $request->user());
    }
}
