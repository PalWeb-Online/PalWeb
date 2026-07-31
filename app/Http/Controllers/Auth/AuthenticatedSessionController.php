<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserAuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'user' => new UserAuthResource($request->user()),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'user' => new UserAuthResource($user),
            'csrf_token' => csrf_token(),
        ]);
    }
}
