<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::get('/user', [AuthenticatedSessionController::class, 'show']);

    Route::prefix('/discord')->controller(OAuthController::class)->group(function () {
        Route::get('/', 'redirect');
        Route::get('/callback', 'callback');
        Route::post('/revoke', 'revoke');
    });

    Route::middleware(['guest'])->group(function () {
        Route::post('/signup', [RegisteredUserController::class, 'store']);
        Route::post('/signin', [AuthenticatedSessionController::class, 'store']);

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
        Route::post('/reset-password', [NewPasswordController::class, 'store']);
    });

    Route::middleware(['auth'])->group(function () {
        Route::patch('/change-password', [NewPasswordController::class, 'update']);

        Route::post('/signout', [AuthenticatedSessionController::class, 'destroy']);
    });
});
