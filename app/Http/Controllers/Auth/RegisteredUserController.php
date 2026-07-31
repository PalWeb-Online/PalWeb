<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Http\Resources\UserAuthResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(StoreRegisteredUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'ar_name' => $request->ar_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'language' => $request->language,
            'dialect_id' => '8',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'user' => new UserAuthResource($user),
            'csrf_token' => csrf_token(),
        ]);
    }
}
