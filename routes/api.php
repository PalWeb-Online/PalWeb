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

Route::prefix('/record-wizard')->controller(RecordWizardController::class)->group(function () {
    Route::post('/stash', 'stashRecord');
    Route::delete('/discard/{stashKey}', 'discardRecord');
    Route::delete('/clear/{speakerId}', 'clearStash');
    Route::post('/upload', 'uploadRecords');
});

Route::post('/api/discord/joined', [DiscordController::class, 'joined']);
