<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Flasher\Prime\FlasherInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function store(StoreRegisteredUserRequest $request): RedirectResponse
    {

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

        return redirect()->to(AppServiceProvider::HOME);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.signup');
    }
}
