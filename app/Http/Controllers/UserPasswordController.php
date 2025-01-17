<?php

namespace App\Http\Controllers;

use App\Events\PasswordChanged;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;

class UserPasswordController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function edit(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Settings: Change Password');

        return view('users.dashboard.change-password', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = auth()->user();

        $form = $request->validate([
            'password_new' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = Hash::make($form['password_new']);
        $user->save();

        event(new PasswordChanged($user));

        $this->flasher->addSuccess(__('passwords.updated'));

        return to_route('users.show', auth()->user());
    }
}
