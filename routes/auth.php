<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth/discord')->controller(OAuthController::class)->group(function () {
    Route::get('/', 'redirect')->name('auth.discord');
    Route::get('/callback', 'callback')->name('auth.discord.callback');
    Route::post('/revoke', 'revoke')->name('auth.discord.revoke');
});

Route::middleware('guest')->group(function () {

    Route::get('signup', [RegisteredUserController::class, 'create'])
        ->name('signup');
    Route::post('signup', [RegisteredUserController::class, 'store']);

    Route::get('signin', [AuthenticatedSessionController::class, 'create'])
        ->name('signin');
    Route::post('signin', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('signout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('signout');
});
