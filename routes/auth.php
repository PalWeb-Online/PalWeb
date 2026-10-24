<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::get('/user', [AuthenticatedSessionController::class, 'show'])->name('auth.user');

    Route::prefix('/discord')->controller(OAuthController::class)->group(function () {
        Route::get('/', 'redirect')->name('auth.discord');
        Route::get('callback', 'callback')->name('auth.discord.callback');
        Route::post('revoke', 'revoke')->name('auth.discord.revoke');
    });

    Route::middleware(['guest'])->group(function () {
        Route::post('signup', [RegisteredUserController::class, 'store'])->name('signup');
        Route::post('signin', [AuthenticatedSessionController::class, 'store'])->name('signin');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    });

    Route::middleware(['auth'])->group(function () {
        Route::patch('change-password', [NewPasswordController::class, 'update'])
            ->name('password.change');

        Route::post('signout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('signout');
    });
});
