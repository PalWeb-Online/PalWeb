<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UserAvatarController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\PinBoardController;
use App\Http\Controllers\DeckMasterController;
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
use App\Http\Controllers\DialogController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPrivacyController;
use App\Http\Controllers\WikiController;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Sentence;
use App\Models\Term;
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

Route::view('/', 'index', ['bodyBackground' => 'front-page'])->middleware('pageTitle:Home')->name('homepage');

Route::view('/denied', 'denied',
    ['bodyBackground' => 'hero-yellow'])->middleware('pageTitle:Access Denied')->name('denied');

Route::post('/lang/{lang}', [LanguageController::class, 'store'])->name('language.store');

Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
    Route::post('/', 'getResults');
    Route::get('/filter-options', 'getFilterOptions');
});

Route::prefix('/email')->middleware('auth')->group(function () {
    Route::controller(EmailVerificationController::class)->group(function () {
        Route::get('/verification', 'prompt')->name('verification.notice');
        Route::get('/verification/{id}/{hash}', 'verify')->middleware([
            'signed', 'throttle:6,1',
        ])->name('verification.verify');
        Route::post('/verification/link', 'link')->middleware('throttle:6,1')->name('verification.send');
    });
});

Route::prefix('/email')->middleware('admin')->group(function () {
    Route::controller(EmailAnnouncementController::class)->group(function () {
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
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified'])->name('terms.pin');

//        todo: overwrites getter for Term Editor
        Route::get('/{term}/get', function (Term $term) {
            return new TermResource(Term::findOrFail($term->id));
        })->name('terms.get');
    });

    Route::get('/random', function () {
        return to_route('terms.show', \App\Models\Term::inRandomOrder()->first());
    })->name('terms.random');
});

Route::prefix('/wiki')->controller(WikiController::class)->group(function () {
    Route::get('/', 'index')->name('wiki.index');
    Route::get('/{page}', 'show')->name('wiki.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/academy')->group(function () {
        Route::prefix('/lessons')->controller(UnitController::class)->group(function () {
            Route::get('/', 'index')->name('academy.index');
            Route::get('/{unit}', 'unit')->name('academy.unit');
            Route::get('/{unit}/{lesson}', 'lesson')->name('academy.lesson');
        });
        Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
            Route::get('/', 'index')->name('dialogs.index');
            Route::get('/{dialog}', 'show')->name('dialogs.show');

            Route::get('/{dialog}/get', function (Dialog $dialog) {
                return new DialogResource(
                    Dialog::with(['sentences'])->findOrFail($dialog->id)
                );
            })->name('dialogs.get');
        });

        Route::middleware('admin')->group(function () {
            Route::resource('/dialogs', DialogController::class)->except(['index', 'show', 'create']);
            Route::get('/create/dialogs', [DialogController::class, 'create'])->name('dialogs.create');
        });
    });

    Route::prefix('/dictionary')->group(function () {
        Route::controller(MissingTermController::class)->group(function () {
            Route::get('/missing/terms/create', 'create')->name('missing.terms.create');
            Route::post('/missing/terms', 'store')->name('missing.terms.store');
        });

        Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
            Route::get('/', 'index')->name('sentences.index');
            Route::get('/{sentence}', 'show')->name('sentences.show');
            Route::post('/{sentence}/pin', 'pin')->name('sentences.pin');

            Route::get('/{sentence}/get', function (Sentence $sentence) {
                return new SentenceResource(
                    Sentence::with(['dialog'])->findOrFail($sentence->id)
                );
            })->name('sentences.get');
        });

        Route::prefix('/explore')->controller(ExploreController::class)->group(function () {
            Route::get('/', 'index')->name('explore.index');
            Route::get('/{page}', 'show')->name('explore.show');
        });

        Route::middleware('admin')->group(function () {
            Route::resource('/terms', TermController::class)->except(['index', 'show', 'create']);
            Route::get('/create/terms', [TermController::class, 'create'])->name('terms.create');

            Route::resource('/sentences', SentenceController::class)->except(['index', 'show', 'create']);
            Route::get('/create/sentences', [SentenceController::class, 'create'])->name('sentences.create');

            Route::get('/missing/terms', [MissingTermController::class, 'index'])->name('missing.terms.index');
            Route::delete('/missing/terms/{missingTerm}',
                [MissingTermController::class, 'destroy'])->name('missing.terms.destroy');
            Route::get('/missing/sentences', [SentenceController::class, 'todo'])->name('missing.sentences.index');
        });
    });

    Route::prefix('/community')->group(function () {
        Route::get('/',
            [CommunityController::class, 'index'])->middleware('pageTitle:Community')->name('community.index');

        Route::get('/users/{user:username}', [UserController::class, 'show'])->name('users.show');

        Route::resource('/decks', DeckController::class)->except(['create', 'edit']);
        Route::prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::post('/{deck}/pin', 'pin')->name('decks.pin');
            Route::post('/{deck}/copy', 'copy')->name('decks.copy');
            Route::post('/{deck}/export', 'export')->name('decks.export');
            Route::post('/{deck}/toggle/{term}', 'toggleTerm')->name('decks.term.toggle');
            Route::patch('/{deck}/privacy', 'togglePrivacy')->name('decks.privacy.toggle');

            Route::get('/{deck}/get', function (Deck $deck) {
                return new DeckResource(
                    Deck::with(['author', 'terms'])->findOrFail($deck->id)
                );
            })->name('decks.get');
        });

        Route::prefix('/audios')->group(function () {
            Route::get('/', [AudioController::class, 'index'])->name('audios.index');
            Route::delete('/{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');
            Route::get('/{speaker}', [SpeakerController::class, 'show'])->name('speaker.show');
        });
    });

    Route::prefix('/workbench')->group(function () {
        Route::get('/pin-board', [PinBoardController::class, 'index'])->name('workbench.index');

        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/', 'index')->name('deck-master.index');
            Route::get('/build', 'create')->name('deck-master.create');
            Route::get('/build/{deck}', 'edit')->name('deck-master.edit');
            Route::get('/study/{deck}', 'study')->name('deck-master.study');
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

    Route::get('/subscription', function () {
        View::share('pageTitle', 'Subscription');
        return view('users.dashboard.subscription', [
            'user' => auth()->user(),
            'bodyBackground' => 'hero-yellow',
        ]);
    })->name('subscription.index');
});

Route::prefix('/settings')->middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('settings.profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('settings.profile.update');
    Route::get('/password', [UserPasswordController::class, 'edit'])->name('settings.password.edit');
    Route::patch('/password', [UserPasswordController::class, 'update'])->name('settings.password.update');
    Route::get('/avatar', [UserAvatarController::class, 'edit'])->name('settings.avatar.edit');
    Route::patch('/avatar', [UserAvatarController::class, 'update'])->name('settings.avatar.update');
    Route::patch('/privacy', [UserPrivacyController::class, 'update'])->name('users.privacy.toggle');
});

require __DIR__.'/auth.php';
