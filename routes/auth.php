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
    Route::post('signup', [RegisteredUserController::class, 'store'])->name('signup');
    Route::post('signin', [AuthenticatedSessionController::class, 'store'])->name('signin');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

Route::controller(NewPasswordController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/reset-password/{token}', 'show')
            ->name('password.create');
        Route::post('/reset-password', 'store')
            ->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/change-password', 'show')
            ->name('password.edit');
        Route::patch('/change-password', 'update')
            ->name('password.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('signout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('signout');
});
