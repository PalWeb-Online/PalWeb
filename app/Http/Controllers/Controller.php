<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Throwable;

abstract class Controller
{
    protected function failureJsonResponse(string $message, Throwable $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $e instanceof AuthorizationException ? 403 : 500);
    }
}
