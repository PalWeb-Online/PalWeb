<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            session()->flash('notification',
                ['type' => 'success', 'message' => __('signin.message', ['user' => auth()->user()->name])]);
            return back();
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $name = auth()->user()->name;
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('signout.message', ['user' => $name])]);
        return to_route('homepage');
    }
}
