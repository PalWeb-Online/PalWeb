<?php

use App\Http\Controllers\Academy\ActivityController;
use App\Http\Controllers\Academy\DialogController;
use App\Http\Controllers\Academy\LessonController;
use App\Http\Controllers\Academy\ScoreController;
use App\Http\Controllers\Academy\AcademyStateController;
use App\Http\Controllers\Academy\UnitController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\EmailAnnouncementController;
use App\Http\Controllers\FeedbackCommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Office\LessonPlannerController;
use App\Http\Controllers\Office\SpeechMakerController;
use App\Http\Controllers\Office\WordLoggerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\SearchGenieController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Workbench\CardDealerController;
use App\Http\Controllers\Workbench\DeckMasterController;
use App\Http\Controllers\Workbench\SoundBoothController;
use App\Http\Resources\AudioResource;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DialogResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Models\Term;
use App\Services\SentenceService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/wiki')->controller(PageController::class)->group(function () {
    Route::middleware('admin')->group(function () {
        Route::post('/', 'store')->name('wiki.store');
        Route::patch('/{page}', 'update')->name('wiki.update');
        Route::delete('/{page}', 'destroy')->name('wiki.destroy');
    });
});

Route::post('/lang/{lang}', [LanguageController::class, 'store'])->name('language.store');

Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
    Route::post('/', 'getResults');
    Route::get('/filter-options', 'getFilterOptions');
});

Route::prefix('/email')->middleware('auth')->controller(EmailVerificationController::class)->group(function () {
    Route::get('/verification', function () {
        return to_route('users.show', auth()->user())->with('notification', [
            'type' => 'warning',
            'message' => 'You must verify your email to access this feature.',
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
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified'])->name('terms.pin');

        Route::get('/{term}/get', function (Term $term) {
            return new TermResource(Term::findOrFail($term->id));
        })->name('terms.get');

        Route::get('/{term}/get/sentences/{gloss}', 'getSentences')->name('terms.get.sentences');
        Route::get('/{term}/get/pronunciations', 'getPronunciations')->name('terms.get.pronunciations');
        Route::get('/{pronunciation}/get/audios', function (Pronunciation $pronunciation) {
            return AudioResource::collection($pronunciation->audios);
        })->name('terms.get.pronunciations.audios');
    });

    Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
        Route::post('/{sentence}/pin', 'pin')->middleware(['auth', 'verified'])->name('sentences.pin');
        Route::post('/get-many', 'getMany')->name('sentences.get-many');
    });

});

Route::middleware(['auth'])->prefix('/hub')->group(function () {
    Route::patch('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::prefix('/users')->group(function () {
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/{user:username}/avatars', [AvatarController::class, 'index'])->name('users.avatars.index');
        Route::post('/{user:username}/avatars', [AvatarController::class, 'store'])->name('users.avatars.store');
        Route::post('/{user:username}/teacher', [TeacherController::class, 'store'])->name('users.teacher.store');
    });

    Route::delete('/avatars/{avatar}', [AvatarController::class, 'destroy'])->name('avatars.destroy');

    Route::get('/avatars/get', function () {
        $avatars = File::files(public_path('img/avatars'));

        return array_map(function ($file) {
            return [
                'url' => asset('img/avatars/'.$file->getFilename()),
                'filename' => $file->getFilename(),
            ];
        }, $avatars);
    })->name('avatars.get');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/academy')->middleware(['student'])->group(function () {
        Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
            Route::get('/{dialog}/get', function (Dialog $dialog) {
                return new DialogResource(
                    Dialog::with(['sentences'])->findOrFail($dialog->id)
                );
            })->name('dialogs.get');
        });

        Route::prefix('/scores')->controller(ScoreController::class)->group(function () {
            Route::post('/', 'store')->name('scores.store');
            Route::post('/purge', 'purge')->name('scores.purge');
            Route::delete('/{score}', 'destroy')->name('scores.destroy');
        });
    });

    Route::prefix('/library')->group(function () {
        Route::resource('/decks', DeckController::class)->except(['index', 'show', 'create', 'edit']);
        Route::prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::post('/{deck}/pin', 'pin')->name('decks.pin');
            Route::post('/{deck}/copy', 'copy')->name('decks.copy');
            Route::post('/{deck}/export', 'export')->name('decks.export');
            Route::post('/{deck}/toggle/{term}', 'toggleTerm')->name('decks.term.toggle');

            Route::get('/{deck}/get', function (Deck $deck) {
                return new DeckResource(Deck::with([
                    'author',
                    'terms' => fn ($q) => $q->whereNotNull('deck_term.gloss_id'),
                ])->findOrFail($deck->id));
            })->name('decks.get');
        });

        Route::prefix('/audios')->group(function () {
            Route::delete('/{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');
        });

    });

    Route::prefix('/workbench')->group(function () {
        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/get-decks', 'getDecks')->name('deck-master.get-decks');
            Route::get('/study/{deck}/getCards', 'getCards')->name('deck-master.get-cards');
            Route::post('/study/{deck}/getQuiz', 'getQuiz')->name('deck-master.get-quiz');
        });

        Route::prefix('/cards')->controller(CardController::class)->group(function () {
            Route::post('/purge', 'purge')->name('cards.purge');

            Route::middleware('can:update,card')->group(function () {
                Route::post('/{card}', 'update')->name('cards.update');
                Route::post('/{card}/master', 'master')->name('cards.master');
                Route::post('/{card}/suspend', 'suspend')->name('cards.suspend');
                Route::post('/{card}/reset', 'reset')->name('cards.reset');
                Route::delete('/{card}', 'destroy')->name('cards.destroy');
            });
        });

        Route::prefix('/sound-booth')->group(function () {
            Route::controller(SoundBoothController::class)->group(function () {
                Route::get('/speaker', 'fetchSpeaker')->name('sound-booth.speaker');
                Route::post('/pronunciations', 'getAutoItems')->name('sound-booth.get.auto');
                Route::post('/decks/{deck}', 'getDeckItems')->name('sound-booth.get.deck');
            });
            Route::controller(SpeakerController::class)->group(function () {
                Route::post('/speaker', 'store')->name('speaker.store');
                Route::get('/options', 'getSpeakerOptions')->name('speaker.options');
            });
        });
    });

    Route::prefix('/office')->middleware(['admin'])->group(function () {
        Route::resource('/terms', TermController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('/sentences', SentenceController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('/dialogs', DialogController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('/units', UnitController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('/lessons', LessonController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('/activities', ActivityController::class)->except(['index', 'show', 'create', 'edit']);

        Route::prefix('/word-logger')->controller(WordLoggerController::class)->group(function () {
            Route::get('/relatives', 'relatives')->name('word-logger.relatives');
            Route::get('/sentences', 'sentences')->name('word-logger.sentences');
            Route::get('/inflections', 'inflections')->name('word-logger.inflections');
            Route::get('/editor-data', 'editorData')->name('word-logger.editor-data');
        });

        Route::prefix('/speech-maker')->controller(SpeechMakerController::class)->group(function () {
            Route::get('/get', 'apiIndex')->name('speech-maker.get');
            Route::get('/get-terms/{id}', function (string $sentenceId) {
                return response()->json(['terms' => app(SentenceService::class)->getSentenceTerms(Sentence::findOrFail($sentenceId))->toArray()]);
            })->name('speech-maker.get-terms');
        });

        Route::prefix('/lesson-planner')->controller(LessonPlannerController::class)->group(function () {
            Route::get('/get', 'apiCourse')->name('lesson-planner.get');
            Route::patch('/', 'update')->name('lesson-planner.update');
            Route::get('/unit/{unit}/decks/get', 'apiUnitDecks')->name('lesson-planner.unit-decks.get');
        });

        Route::prefix('/feedback')->controller(FeedbackCommentController::class)->group(function () {
            Route::get('/get', 'index')->name('feedback.get');
            Route::post('/', 'store')->name('todo.store');
            Route::delete('/{feedbackComment}', 'destroy')->name('todo.destroy');
        });
    });

    Route::get('/get-decks', [UserController::class, 'getDecks'])->name('users.decks.get');
    Route::patch('/update-preferences', [UserController::class, 'updatePreferences'])->name('users.preferences.update');
    Route::get('/toggle-view/{role?}', [UserController::class, 'toggleView'])->name('admin.toggle-view');
});

Route::prefix('/api')->group(function () {
    Route::get('/home', [HomeController::class, 'apiIndex'])->name('api.home.index');

    Route::middleware(['auth', 'verified', 'student'])->prefix('/academy')->group(function () {
        Route::get('/state', [AcademyStateController::class, 'show'])->name('api.academy.state');
        Route::get('/units', [UnitController::class, 'index'])->name('api.academy.units.index');
        Route::get('/dialogs', [DialogController::class, 'index'])->name('api.academy.dialogs.index');
        Route::get('/scores', [ScoreController::class, 'apiIndex'])->name('api.academy.scores.index');
        Route::get('/scores/{scorable_type}/{scorable_id}',
            [ScoreController::class, 'apiHistory'])->name('api.academy.scores.history');
    });

    Route::middleware('auth')->get('/community', [CommunityController::class, 'apiIndex'])->name('api.community.index');

    Route::prefix('/users')->controller(UserController::class)->group(function () {
        Route::get('/{user:username}', 'show')->name('api.users.show');
        Route::get('/{user}/edit', 'edit')->whereNumber('user')->name('api.users.edit');
        Route::patch('/{user}/roles/toggle-student', 'toggleStudentRole')->name('api.users.roles.toggle-student');
    });

    Route::prefix('/activities')->controller(ActivityController::class)->group(function () {
        Route::get('/{activity}', 'show')->name('api.activities.show');
        Route::get('/{activity}/edit', 'edit')->whereNumber('activity')->name('api.activities.edit');
    });

    Route::prefix('/decks')->controller(DeckController::class)->group(function () {
        Route::get('/search', 'search')->name('api.decks.search');
    });

    Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
        Route::get('/search', 'search')->name('api.dialogs.search');
        Route::get('/{dialog}', 'show')->name('api.dialogs.show');
        Route::get('/{dialog}/edit', 'edit')->whereNumber('dialog')->name('api.dialogs.edit');
    });

    Route::prefix('/lessons')->controller(LessonController::class)->group(function () {
        Route::get('/{lesson:global_position}', 'show')->name('api.lessons.show');
        Route::get('/{lesson}/edit', 'edit')->whereNumber('lesson')->name('api.lessons.edit');
    });

    Route::prefix('/units')->controller(UnitController::class)->group(function () {
        Route::get('/search', 'search')->name('api.units.search');
        Route::get('/{unit}', 'show')->name('api.units.show');
        Route::get('/{unit}/edit', 'edit')->whereNumber('unit')->name('api.units.edit');
    });

    Route::prefix('/wiki')->controller(PageController::class)->group(function () {
        Route::get('/tree', 'getWikiTree')->name('api.wiki.tree');
        Route::get('/search', 'search')->name('api.wiki.search');
        Route::get('/{page:slug}', 'show')->name('api.wiki.show');
        Route::get('/{page}/edit', 'edit')->whereNumber('page')->name('api.wiki.edit');
    });

    Route::prefix('/library')->group(function () {
        Route::prefix('/terms')->controller(TermController::class)->group(function () {
            Route::get('/', 'index')->name('api.terms.index');
            Route::get('/featured', 'showFeatured')->name('api.terms.featured');
            Route::get('/{term:slug}', 'show')->name('api.terms.show');
            Route::get('/{term}/edit', 'edit')->whereNumber('term')->name('api.terms.edit');
        });
        Route::get('/roots/{root}', [RootController::class, 'fetch'])->name('api.roots.fetch');
        Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
            Route::get('/', 'index')->name('api.sentences.index');
            Route::get('/random', 'random')->name('api.sentences.random');
            Route::get('/{sentence}', 'show')->name('api.sentences.show');
            Route::get('/{sentence}/edit', 'edit')->whereNumber('sentence')->name('api.sentences.edit');
        });

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::prefix('/audios')->group(function () {
                Route::get('/', [AudioController::class, 'index'])->name('api.audios.index');
                Route::get('/{speaker}', [SpeakerController::class, 'fetch'])->name('api.speakers.fetch');
            });
            Route::prefix('/decks')->controller(DeckController::class)->group(function () {
                Route::get('/', 'index')->name('api.decks.index');
                Route::get('/random', 'random')->name('api.decks.random');
                Route::get('/{deck}', 'show')->name('api.decks.show');
                Route::get('/{deck}/edit', 'edit')->name('api.decks.edit');
            });
        });
    });

    Route::prefix('/workbench')->group(function () {
        Route::prefix('/card-dealer')->middleware(['student'])->controller(CardDealerController::class)->group(function (
        ) {
            Route::get('/', 'index')->name('api.card-dealer.index');
            Route::post('/get/cards', 'getScopeCards')->name('api.card-dealer.get.cards');
            Route::get('/cards', 'cards')->name('api.cards.index');
            Route::get('/review', 'review')->name('api.card-dealer.review');
        });
    });
});

Route::middleware('auth')
    ->prefix('/push-subscriptions')
    ->controller(PushSubscriptionController::class)
    ->group(function () {
        Route::post('/', 'store')->name('push-subscriptions.store');
        Route::delete('/', 'destroy')->name('push-subscriptions.destroy');
    });

require __DIR__.'/auth.php';
