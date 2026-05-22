<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\StashRecordController;
use App\Http\Controllers\TermController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/library')->group(function () {

    Route::prefix('/terms')->controller(TermController::class)->group(function () {
        Route::get('/', 'apiIndex')->name('api.terms.index');
        Route::get('/{term:slug}', 'apiShow')->name('api.terms.show');
    });

    Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
        Route::get('/', 'apiIndex')->name('api.sentences.index');
        Route::get('/{sentence}', 'apiShow')->name('api.sentences.show');
    });

    Route::prefix('/decks')->controller(DeckController::class)->group(function () {
        Route::get('/', 'apiIndex')->name('api.decks.index');
        Route::get('/{deck}', 'apiShow')->name('api.decks.show');
    });

    Route::prefix('/audios')->group(function () {
        Route::get('/', [AudioController::class, 'apiIndex'])->name('api.audios.index');
        Route::get('/{speaker}', [SpeakerController::class, 'apiShow'])->name('api.speaker.show');
    });

});