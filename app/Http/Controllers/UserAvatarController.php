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

    public function edit(): \Illuminate\View\View
    {
        $avatars = File::files(public_path('img/avatars'));
        $avatars = array_map(function ($file) {
            return basename($file);
        }, $avatars);

        View::share('pageTitle', 'Settings: Change Avatar');

        return view('users.dashboard.change-avatar', [
            'user' => auth()->user(),
            'avatars' => $avatars,
        ]);
    }

    public function update(Request $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = auth()->user();

        $user->avatar = $request['avatar'];
        $user->save();

        $flasher->addSuccess(__('settings.updated'));

        return to_route('users.show', auth()->user());
    }
}
