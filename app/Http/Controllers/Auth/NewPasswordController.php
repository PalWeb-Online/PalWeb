<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\StoreNewPasswordRequest;
use App\Http\Controllers\Controller;
use Flasher\Prime\FlasherInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreNewPasswordRequest $request): RedirectResponse
    {

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'token') + ['password' => $request->password_new],
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password_new),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.

        if ($status == Password::PASSWORD_RESET) {
            $this->flasher->addSuccess($status);

            return to_route('signin');
        } else {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }
    }
}
