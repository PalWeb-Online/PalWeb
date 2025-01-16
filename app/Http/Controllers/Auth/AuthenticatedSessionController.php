<?php

namespace App\Http\Controllers\Auth;

use App\Providers\AppServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.signin');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            $this->flasher->addFlash('info', __('signin.message', ['user' => $user->name]), __('signin.message.head'));

            return redirect()->intended(AppServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $this->flasher->addFlash('info', __('signout.message', ['user' => $user->name]), __('signout.message.head'));

        return to_route('homepage');
    }
}
