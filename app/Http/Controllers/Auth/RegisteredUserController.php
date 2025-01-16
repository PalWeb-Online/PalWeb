<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Flasher\Prime\FlasherInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Handle an incoming registration request.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', new LatinScript],
            'ar_name' => ['required', 'string', 'max:50', new ArabicScript],
            'username' => [
                'required', 'string', 'max:50',
                'regex:/^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/',
                'unique:users',
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'ar_name' => $request->ar_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dialect_id' => '1',
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->flasher->addFlash('info', __('signup.message', ['user' => $user->name]),
            __('signup.message.head'));

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.signup');
    }
}
