<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    public function store(StorePasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'token') + ['password' => $request->password],
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                Auth::login($user);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('notification', ['type' => 'success', 'message' => 'New password has been set!']);

            return to_route('users.show', $request->user());

        } else {
            session()->flash('notification', ['type' => 'error', 'message' => 'Failed to reset password.']);

            return back();
        }
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->password_new),
        ])->save();

        event(new PasswordReset($user));

        session()->flash('notification', ['type' => 'success', 'message' => 'New password has been set!']);

        return to_route('users.show', $request->user());
    }
}
