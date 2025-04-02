<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\DialogController;
use App\Http\Controllers\EmailAnnouncementController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\SearchGenieController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Workbench\DeckMasterController;
use App\Http\Controllers\Workbench\RecordWizardController;
use App\Http\Controllers\Workbench\SpeechMakerController;
use App\Http\Resources\AudioResource;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Http\Resources\UserResource;
use App\Models\Audio;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Index', [
        'count' => [
            'terms' => Term::count(),
            'sentences' => Sentence::count(),
            'users' => User::count(),
            'decks' => Deck::count(),
            'dialogs' => Dialog::count(),
            'audios' => Audio::count(),
        ],
        'users' => UserResource::collection(User::find([7, 10, 11, 18, 19, 878, 1113, 1115, 1186, 1224])->all()),
        'decks' => DeckResource::collection(Deck::find([2, 3, 4, 12, 19, 83, 100, 118])->load(['terms'])->all()),
        'sentences' => SentenceResource::collection(Sentence::find([255, 256, 257])->all()),
        'featuredUser' => new UserResource(User::find(1)),
        'featuredDeck' => new DeckResource(Deck::find(56)->load(['terms'])),
    ]);
})->name('homepage');

Route::get('/wiki/{page}', function ($page) {
//    View::share('pageDescription', 'Dive into the most detailed publicly-accessible descriptive grammar of Palestinian Arabic ever; practical enough for learners, rigorous enough for linguists. Everything you need to understand the intricacies of the language is right here.');

    $componentPath = resource_path("js/Pages/Wiki/Pages/{$page}.vue");

    if (file_exists($componentPath)) {
        return Inertia::render("Wiki/Pages/{$page}", [
            'section' => 'wiki',
            'page' => $page
        ]);
    }

    return Inertia::render('Error', ['status' => 404]);
})->name('wiki.show');

Route::get('/coming-soon', function () {
    return Inertia::render('ComingSoon');
})->name('coming-soon');

Route::get('/denied', function () {
    return Inertia::render('Auth/Subscription', [
        'denied' => true,
    ]);
})->middleware('guest')->name('denied');

Route::post('/lang/{lang}', [LanguageController::class, 'store'])->name('language.store');

Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
    Route::post('/', 'getResults');
    Route::get('/filter-options', 'getFilterOptions');
});

Route::prefix('/email')->middleware('auth')->controller(EmailVerificationController::class)->group(function () {
    Route::get('/verification', function () {
        return to_route('users.show', auth()->user())->with('notification', [
            'type' => 'warning',
            'message' => 'You must verify your email to access this feature.'
        ]);
    })->name('verification.notice');
    Route::post('/verification/link', 'link')
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/verification/{id}/{hash}', 'verify')
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
});

Route::post('/email', [EmailAnnouncementController::class, 'store'])
    ->middleware('admin')->name('email.store');

Route::prefix('/library')->controller(TermController::class)->group(function () {
    Route::prefix('/terms')->group(function () {
        Route::get('/', 'index')->name('terms.index');
        Route::get('/{term:slug}', 'show')->name('terms.show');
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified'])->name('terms.pin');

        Route::get('/{term}/get', function (Term $term) {
            return new TermResource(Term::findOrFail($term->id));
        })->name('terms.get');

//        todo: should these be API routes?
        Route::get('/{term}/get/sentences/{gloss}', 'getSentences')->name('terms.get.sentences');
        Route::get('/{term}/get/pronunciations', 'getPronunciations')->name('terms.get.pronunciations');
        Route::get('/{pronunciation}/get/audios', function (Pronunciation $pronunciation) {
            return AudioResource::collection($pronunciation->audios);
        })->name('terms.get.pronunciations.audios');
    });

    Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
        Route::get('/', 'index')->name('sentences.index');
        Route::get('/{sentence}', 'show')->name('sentences.show');
        Route::post('/{sentence}/pin', 'pin')->middleware(['auth', 'verified'])->name('sentences.pin');

        Route::get('/{sentence}/get', function (Sentence $sentence) {
            return new SentenceResource(
                Sentence::with(['dialog'])->findOrFail($sentence->id)
            );
        })->name('sentences.get');
    });

    Route::get('/roots/{root}', [RootController::class, 'show'])->name('roots.show');

    Route::get('/random', function () {
        return to_route('terms.show', Term::inRandomOrder()->first());
    })->name('terms.random');
});

Route::middleware(['auth'])->prefix('/hub')->group(function () {
    Route::prefix('/users')->group(function () {
        Route::get('/', [CommunityController::class, 'index'])->name('users.index');
        Route::get('/{user:username}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user:username}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{user:username}', [UserController::class, 'update'])->name('users.update');
    });

    Route::get('/avatars/get', function () {
        $avatars = File::files(public_path('img/avatars'));

        return array_map(function ($file) {
            return basename($file);
        }, $avatars);
    })->name('avatars.get');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/academy')->middleware(['student'])->group(function () {
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
            Route::resource('/dialogs', DialogController::class)->except(['index', 'show', 'create', 'edit']);
        });

        Route::prefix('/explore')->controller(ExploreController::class)->group(function () {
            Route::get('/', 'index')->name('explore.index');
            Route::get('/{page}', 'show')->name('explore.show');
        });
    });

    Route::prefix('/library')->group(function () {
        Route::resource('/decks', DeckController::class)->except(['create', 'edit']);
        Route::prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::post('/{deck}/pin', 'pin')->name('decks.pin');
            Route::post('/{deck}/copy', 'copy')->name('decks.copy');
            Route::post('/{deck}/export', 'export')->name('decks.export');
            Route::post('/{deck}/toggle/{term}', 'toggleTerm')->name('decks.term.toggle');

            Route::get('/{deck}/get', function (Deck $deck) {
                return new DeckResource(Deck::with(['author', 'terms'])->findOrFail($deck->id));
            })->name('decks.get');
        });

        Route::prefix('/audios')->group(function () {
            Route::get('/', [AudioController::class, 'index'])->name('audios.index');
            Route::delete('/{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');
            Route::get('/{speaker}', [SpeakerController::class, 'show'])->name('speaker.show');
        });

        Route::middleware('admin')->group(function () {
            Route::resource('/terms', TermController::class)->except(['index', 'show', 'create']);
            Route::get('/create/terms', [TermController::class, 'create'])->name('terms.create');

            Route::resource('/sentences', SentenceController::class)->except(['index', 'show', 'create', 'edit']);
        });
    });

    Route::prefix('/workbench')->group(function () {
        Route::prefix('/speech-maker')->controller(SpeechMakerController::class)->group(function () {
            Route::get('/dialog/{dialog?}', 'dialog')->name('speech-maker.dialog');
            Route::get('/dialog/{dialog}/sentence', 'dialogSentence')->name('speech-maker.dialog-sentence');
            Route::get('/sentence/{sentence?}', 'sentence')->name('speech-maker.sentence');
            Route::get('/get-terms/{id}', function (string $sentenceId) {
                return response()->json(['terms' => Sentence::findOrFail($sentenceId)->getTerms()]);
            })->name('speech-maker.get-terms');
        })->middleware(['admin']);

        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/', 'index')->name('deck-master.index');
            Route::get('/get-decks', 'getDecks')->name('deck-master.get-decks');
            Route::get('/build/{deck?}', 'build')->name('deck-master.build');
            Route::get('/study/{deck}', 'study')->name('deck-master.study');
        });

        Route::prefix('/record-wizard')->group(function () {
            Route::controller(RecordWizardController::class)->group(function () {
                Route::get('/', 'index')->name('audios.record');
                Route::post('/pronunciations', 'getAutoItems');
                Route::post('/decks/{deck}', 'getDeckItems');
            });
            Route::controller(SpeakerController::class)->group(function () {
                Route::post('/speaker', 'store')->name('speaker.store');
                Route::get('/speaker', 'getSpeaker');
                Route::get('/options', 'getSpeakerOptions');
            });
        });
    });

    Route::get('/subscription', function () {
        return Inertia::render('Auth/Subscription', [
            'section' => 'account',
            'user' => auth()->user(),
        ]);
    })->name('subscription.index');

    Route::prefix('/todo')->controller(ToDoController::class)->group(function () {
        Route::post('/', 'store')
            ->name('todo.store');
        Route::get('/', 'index')
            ->middleware('admin')->name('todo.index');
        Route::delete('/{feedbackComment}', 'destroy')
            ->middleware('admin')->name('todo.destroy');
    });
});

require __DIR__.'/auth.php';
