<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePasswordResetLinkRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function store(StorePasswordResetLinkRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return response()->json([
            'success' => $status === Password::RESET_LINK_SENT,
            'status' => $status,
        ]);
    }
}
