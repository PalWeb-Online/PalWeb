<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    public function store(StorePasswordRequest $request): JsonResponse
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

        return response()->json([
            'success' => $status === Password::PASSWORD_RESET
        ]);
    }

    public function update(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->password_new),
        ])->save();

        event(new PasswordReset($user));

        return response()->json([
            'success' => true
        ]);
    }
}
