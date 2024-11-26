<?php

use App\Http\Controllers\DiscordController;
use App\Http\Controllers\RecordWizardController;
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

Route::prefix('/record')->controller(RecordWizardController::class)->group(function () {
    Route::post('/stash', 'stash');
    Route::delete('/clear-stash', 'clearStash');
    Route::delete('/discard-record/{stashkey}', 'discardRecord');
});

Route::post('/api/discord/joined', [DiscordController::class, 'joined']);
