<?php

use App\Http\Controllers\DiscordController;
use Illuminate\Support\Facades\Route;

Route::post('/api/discord/joined', [DiscordController::class, 'joined']);
Route::post('/auth/discord/joined', [DiscordController::class, 'joined']);
