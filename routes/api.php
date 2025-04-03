<?php

use App\Http\Controllers\DiscordController;
use App\Http\Controllers\StashRecordController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/record-wizard')->controller(StashRecordController::class)->group(function () {
    Route::post('/store', 'store')->name('stash.store');
    Route::post('/upload', 'upload')->name('stash.upload');
    Route::delete('/{stashKey}', 'destroy')->name('stash.destroy');
    Route::delete('/clear/{speakerId}', 'clearStash')->name('stash.clear');
});

Route::post('/api/discord/joined', [DiscordController::class, 'joined']);
