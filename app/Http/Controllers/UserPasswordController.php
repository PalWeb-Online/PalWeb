<?php

namespace App\Http\Controllers;

use App\Events\PasswordChanged;
use App\Http\Requests\UpdateUserPasswordRequest;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;

class UserPasswordController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function edit(Request $request): \Illuminate\View\View
    {
        View::share('pageTitle', 'Settings: Change Password');

        return view('users.dashboard.change-password', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateUserPasswordRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        $user = $request->user();

        $form = $request->validated();

        $user->password = Hash::make($form['password_new']);
        $user->save();

        event(new PasswordChanged($user));

        $this->flasher->addSuccess(__('passwords.updated'));

        return to_route('users.show', $request->user());
    }
}
