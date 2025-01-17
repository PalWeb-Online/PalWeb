<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CardViewerController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UserAvatarController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\WorkbenchController;
use App\Http\Controllers\DeckBuilderController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\EmailAnnouncementController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MissingTermController;
use App\Http\Controllers\RecordWizardController;
use App\Http\Controllers\SearchGenieController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Displays the homepage.
 */
Route::get('/', function () {
    return view('index', [
        'bodyBackground' => 'front-page',
    ]);
})->middleware('pageTitle:Home')->name('homepage');


Route::get('/unauth', function () {
    return view('unauth', [
        'bodyBackground' => 'hero-yellow',
    ]);
})->middleware('pageTitle:Access Denied')->name('unauth');

Route::post('/lang/{lang}', [LanguageController::class, 'store'])->name('language.store');

Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
    Route::post('/', 'getResults');
    Route::get('/filter-options', 'getFilterOptions');
});

/**
 * Email Routes
 */
Route::prefix('/email')->middleware('auth')->group(function () {
    Route::controller(EmailVerificationController::class)->group(function () {
        Route::get('/verification', 'prompt')->name('verification.notice');
        Route::get('/verification/{id}/{hash}', 'verify')->middleware([
            'signed', 'throttle:6,1',
        ])->name('verification.verify');
        Route::post('/verification/link', 'link')->middleware('throttle:6,1')->name('verification.send');
    });

    Route::controller(EmailAnnouncementController::class)->middleware('admin')->group(function () {
        Route::get('/create', 'create')->name('email.create');
        Route::post('/store', 'store')->name('email.store');
    });
});

Route::prefix('/dictionary')->controller(TermController::class)->group(function () {
    Route::prefix('/terms')->group(function () {
        Route::get('/', 'index')->name('terms.index');
        Route::get('/{term:slug}', 'show')->name('terms.show');
        Route::get('/{term:slug}/usages', 'show')->name('terms.usages');
        Route::get('/{term:slug}/audios', 'show')->name('terms.audios');

        // Auth
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified'])->name('terms.pin');

        // Admin
        Route::prefix('/admin')->middleware('admin')->group(function () {
            Route::get('/create', 'create')->name('terms.create');
            Route::post('/store', 'store')->name('terms.store');

            Route::get('/{term}/get', 'get')->name('terms.get');
            Route::get('/{term}/edit', 'edit')->name('terms.edit');
            Route::patch('/{term}/update', 'update')->name('terms.update');
            Route::delete('/{term}', 'destroy')->name('terms.destroy');
        });
    });

    Route::get('/random', function () {
        return to_route('terms.show', \App\Models\Term::inRandomOrder()->first());
    })->name('terms.random');
});

/*
 * Community Routes
 */
Route::prefix('/community')->middleware(['auth', 'verified'])->group(function () {
    // Community Routes
    Route::get('/', [CommunityController::class, 'index'])->middleware('pageTitle:Community')->name('community.index');

    // User Routes
    Route::get('/users/{user:username}', [UserController::class, 'show'])->name('users.show');

    // Deck Routes
    Route::resource('/decks', DeckController::class);
    Route::prefix('/decks')->controller(DeckController::class)->group(function () {
        Route::post('/{deck}/pin', 'pin')->name('decks.pin');
        Route::post('/{deck}/copy', 'copy')->name('decks.copy');
        Route::post('/{deck}/export', 'export')->name('decks.export');
        Route::post('/{deck}/toggle/{term}', 'toggleTerm')->name('decks.term.toggle');
        Route::patch('/{deck}/privacy', 'togglePrivacy')->name('decks.privacy.toggle');

    });

    // Audio Routes
    Route::prefix('/audios')->group(function () {
        Route::get('/', [AudioController::class, 'index'])->name('audios.index');
        Route::delete('/{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');
        Route::get('/{speaker}', [SpeakerController::class, 'show'])->name('speaker.show');
    });
});

/**
 * Documentation Routes
 */
Route::prefix('/wiki')->controller(WikiController::class)->group(function () {
    Route::get('/', 'index')->name('wiki.index');
    Route::get('/{page}', 'show')->name('wiki.show');
});

/**
 * Routes that require a logged-in user
 */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('/academy')->group(function () {
        Route::prefix('/lessons')->controller(UnitController::class)->group(function () {
            Route::get('/', 'index')->name('academy.index');
            Route::get('/{unit}', 'unit')->name('academy.unit');
            Route::get('/{unit}/{lesson}', 'lesson')->name('academy.lesson');
        });
        Route::prefix('/texts')->controller(TextController::class)->group(function () {
            Route::get('/', 'index')->name('texts.index');
            Route::get('/{page}', 'show')->name('texts.show');
        });
    });

    Route::prefix('/dictionary')->group(function () {
        Route::middleware('admin')->group(function () {
            Route::get('/todo/terms',
                [TermController::class, 'todo'])->name('terms.todo');
            Route::delete('/todo/terms/{missingTerm}',
                [MissingTermController::class, 'destroy'])->name('missing.destroy');
            Route::get('/todo/sentences',
                [SentenceController::class, 'todo'])->name('sentences.todo');
        });

        Route::get('/request', function () {
            return view('terms.request');
        })->middleware('pageTitle:Request Term')->name('terms.request');
        Route::post('/request', [TermController::class, 'request'])->name('request.store');

        Route::resource('/sentences', SentenceController::class)->except(['index', 'show'])->middleware('admin');
        Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
            Route::get('/', 'index')->name('sentences.index');
            Route::get('/{sentence}', 'show')->name('sentences.show');
            Route::post('/{sentence}/pin', 'pin')->name('sentences.pin');
            Route::get('/{sentence}/get', 'get')->name('sentences.get')->middleware('admin');

        });

        Route::prefix('/explore')->controller(ExploreController::class)->group(function () {
            Route::get('/', 'index')->name('explore.index');
            Route::get('/{page}', 'show')->name('explore.show');
        });
    });

    Route::prefix('/dashboard')->group(function () {
        Route::get('/subscription', function () {
            View::share('pageTitle', 'Subscription');
            return view('users.dashboard.subscription', [
                'user' => auth()->user(),
                'bodyBackground' => 'hero-yellow',
            ]);
        })->name('dashboard.subscription');

        Route::prefix('/workbench')->group(function () {
            Route::get('/', [WorkbenchController::class, 'index'])->name('dashboard.workbench');

            Route::prefix('/deck-builder')->controller(DeckBuilderController::class)->group(function () {
                Route::get('/', 'index')->name('decks.create');
                Route::get('/edit/{deck}', 'edit')->name('decks.edit');
                Route::get('/decks', 'getCreatedDecks');
                Route::get('/decks/{deck}', 'getTerms');
            });

            Route::prefix('/card-viewer')->controller(CardViewerController::class)->group(function () {
                Route::get('/', 'index')->name('decks.study');
                Route::get('/decks', 'getPinnedDecks');
                Route::get('/decks/{deck}', 'getCards');
            });

            Route::prefix('/record-wizard')->group(function () {
                Route::controller(RecordWizardController::class)->group(function () {
                    Route::get('/', 'index')->name('audios.record');
                    Route::post('/pronunciations', 'getAutoItems');
                    Route::post('/decks/{deck}', 'getDeckItems');
                });
                Route::controller(SpeakerController::class)->group(function () {
                    Route::post('/speaker', 'store');
                    Route::get('/speaker', 'getSpeaker');
                    Route::get('/options', 'getSpeakerOptions');
                });
            });
        });

        Route::prefix('/settings')->group(function () {
            Route::get('/profile', [UserSettingsController::class, 'edit'])->name('settings.profile.edit');
            Route::patch('/profile', [UserSettingsController::class, 'update'])->name('settings.profile.update');
            Route::get('/password', [UserPasswordController::class, 'edit'])->name('settings.password.edit');
            Route::patch('/password', [UserPasswordController::class, 'update'])->name('settings.password.update');
            Route::get('/avatar', [UserAvatarController::class, 'edit'])->name('settings.avatar.edit');
            Route::patch('/avatar', [UserAvatarController::class, 'update'])->name('settings.avatar.update');
            Route::patch('/toggle-privacy', [UserSettingsController::class, 'togglePrivacy'])->name('settings.privacy.toggle');
        });

    });
});

require __DIR__.'/auth.php';
