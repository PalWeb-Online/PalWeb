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
use App\Http\Controllers\RuntimeController;
use App\Http\Controllers\SearchGenieController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\StashRecordController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Workbench\CardDealerController;
use App\Http\Controllers\Workbench\DeckMasterController;
use App\Http\Controllers\Workbench\SoundBoothController;
use App\Http\Resources\AudioResource;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Services\SentenceService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/api')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('api.home.index');
    Route::get('/runtime', [RuntimeController::class, 'show'])->name('api.runtime');
    Route::post('/language/{lang}', [LanguageController::class, 'store'])->name('language.store');

    Route::prefix('/terms')->controller(TermController::class)->group(function () {
        Route::get('/', 'index')->name('api.terms.index');
        Route::get('/random', 'random')->name('api.terms.random');
        Route::get('/featured', 'showFeatured')->name('api.terms.featured');
        Route::get('/{term:slug}', 'show')->name('api.terms.show');
        Route::get('/{term}/sentences/{gloss}', 'getSentences')->name('terms.get.sentences');
        Route::get('/{term}/pronunciations', 'getPronunciations')->name('terms.get.pronunciations');
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified'])->name('terms.pin');

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store')->name('terms.store');
            Route::get('/{term}/edit', 'edit')->whereNumber('term')->name('api.terms.edit');
            Route::put('/{term}', 'update')->name('terms.update');
            Route::patch('/{term}', 'update');
            Route::delete('/{term}', 'destroy')->name('terms.destroy');
        });
    });

    Route::get('/roots/{root}', [RootController::class, 'fetch'])->name('api.roots.fetch');
    Route::get('/decks/search', [DeckController::class, 'search'])->name('api.decks.search');

    Route::get('/pronunciations/{pronunciation}/audios', function (Pronunciation $pronunciation) {
        return AudioResource::collection($pronunciation->audios);
    })->name('terms.get.pronunciations.audios');

    Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
        Route::get('/', 'index')->name('api.sentences.index');
        Route::get('/random', 'random')->name('api.sentences.random');
        Route::post('/many', 'getMany')->name('sentences.get-many');
        Route::get('/{sentence}', 'show')->name('api.sentences.show');
        Route::post('/{sentence}/pin', 'pin')->middleware(['auth', 'verified'])->name('sentences.pin');

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store')->name('sentences.store');
            Route::get('/{sentence}/edit', 'edit')->whereNumber('sentence')->name('api.sentences.edit');
            Route::put('/{sentence}', 'update')->name('sentences.update');
            Route::patch('/{sentence}', 'update');
            Route::delete('/{sentence}', 'destroy')->name('sentences.destroy');
        });
    });

    Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
        Route::post('/', 'getResults');
        Route::get('/filter-options', 'getFilterOptions');
    });

    Route::prefix('/wiki')->controller(PageController::class)->group(function () {
        Route::get('/tree', 'getWikiTree')->name('api.wiki.tree');
        Route::get('/{page:slug}', 'show')->name('api.wiki.show');

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store')->name('wiki.store');
            Route::get('/search', 'search')->name('api.wiki.search');
            Route::get('/{page}/edit', 'edit')->whereNumber('page')->name('api.wiki.edit');
            Route::patch('/{page}', 'update')->name('wiki.update');
            Route::delete('/{page}', 'destroy')->name('wiki.destroy');
        });
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/community', [CommunityController::class, 'index'])->name('api.community.index');

        Route::prefix('/email')->controller(EmailVerificationController::class)->group(function () {
            Route::get('/verification', fn () => response()->json([
                'message' => 'Email verification is required.',
            ], 403))->name('verification.notice');
            Route::post('/verification/link', 'link')->middleware('throttle:6,1')->name('verification.send');
            Route::get('/verification/{id}/{hash}', 'verify')
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
        });

        Route::prefix('/users')->group(function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/{user:username}', 'show')->name('api.users.show');
                Route::get('/{user}/edit', 'edit')->whereNumber('user')->name('api.users.edit');
                Route::patch('/{user}', 'update')->name('users.update');

                Route::patch('/{user}/roles/toggle-student', 'toggleStudentRole')
                    ->middleware(['verified', 'admin'])
                    ->name('api.users.roles.toggle-student');
            });

            Route::controller(AvatarController::class)->group(function () {
                Route::get('/{user:username}/avatars', 'index')->name('users.avatars.index');
                Route::post('/{user:username}/avatars', 'store')->name('users.avatars.store');
            });
        });

        Route::prefix('/avatars')->group(function () {
            Route::get('/', function () {
                $avatars = File::files(public_path('img/avatars'));

                return array_map(function ($file) {
                    return [
                        'url' => asset('img/avatars/'.$file->getFilename()),
                        'filename' => $file->getFilename(),
                    ];
                }, $avatars);
            })->name('avatars.get');
            Route::delete('/{avatar}', [AvatarController::class, 'destroy'])->name('avatars.destroy');
        });

        Route::prefix('/push-subscriptions')->controller(PushSubscriptionController::class)->group(function (
        ) {
            Route::post('/', 'store')->name('push-subscriptions.store');
            Route::delete('/', 'destroy')->name('push-subscriptions.destroy');
        });
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::prefix('/auth')->controller(UserController::class)->group(function () {
            Route::get('/decks', 'getDecks')->name('users.decks.get');
            Route::patch('/preferences', 'updatePreferences')->name('users.preferences.update');
        });

        Route::prefix('/activities')->controller(ActivityController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/{activity}', 'show')->name('api.activities.show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store')->name('activities.store');
                Route::get('/{activity}/edit', 'edit')->whereNumber('activity')->name('api.activities.edit');
                Route::put('/{activity}', 'update')->name('activities.update');
                Route::patch('/{activity}', 'update');
                Route::delete('/{activity}', 'destroy')->name('activities.destroy');
            });
        });

        Route::prefix('/audios')->controller(AudioController::class)->group(function () {
            Route::get('/', 'index')->name('api.audios.index');
            Route::delete('/{audio}', 'destroy')->name('audios.destroy');
        });

        Route::prefix('/cards')->controller(CardController::class)->group(function () {
            Route::get('/', 'index')->name('api.cards.index');
            Route::post('/purge', 'purge')->name('cards.purge');

            Route::middleware('can:update,card')->group(function () {
                Route::post('/{card}', 'update')->name('cards.update');
                Route::post('/{card}/master', 'master')->name('cards.master');
                Route::post('/{card}/suspend', 'suspend')->name('cards.suspend');
                Route::post('/{card}/reset', 'reset')->name('cards.reset');
                Route::delete('/{card}', 'destroy')->name('cards.destroy');
            });
        });

        Route::prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::get('/', 'index')->name('api.decks.index');
            Route::get('/random', 'random')->name('api.decks.random');
            Route::post('/', 'store')->name('decks.store');
            Route::get('/{deck}', 'show')->name('api.decks.show');
            Route::get('/{deck}/edit', 'edit')->name('api.decks.edit');
            Route::put('/{deck}', 'update')->name('decks.update');
            Route::patch('/{deck}', 'update');
            Route::delete('/{deck}', 'destroy')->name('decks.destroy');
            Route::post('/{deck}/pin', 'pin')->name('decks.pin');
            Route::post('/{deck}/copy', 'copy')->name('decks.copy');
            Route::post('/{deck}/export', 'export')->name('decks.export');
            Route::post('/{deck}/terms/{term}/toggle', 'toggleTerm')->name('decks.term.toggle');
        });

        Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/', 'index')->middleware(['student'])->name('api.academy.dialogs.index');
                Route::get('/{dialog}', 'show')->name('api.dialogs.show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store')->name('dialogs.store');
                Route::get('/search', 'search')->name('api.dialogs.search');
                Route::get('/{dialog}/edit', 'edit')->whereNumber('dialog')->name('api.dialogs.edit');
                Route::put('/{dialog}', 'update')->name('dialogs.update');
                Route::patch('/{dialog}', 'update');
                Route::delete('/{dialog}', 'destroy')->name('dialogs.destroy');
            });
        });

        Route::prefix('/feedback')->controller(FeedbackCommentController::class)->group(function () {
            Route::post('/', 'store')->name('todo.store');

            Route::middleware(['admin'])->group(function () {
                Route::get('/', 'index')->name('feedback.get');
                Route::delete('/{feedbackComment}', 'destroy')->name('todo.destroy');
            });
        });

        Route::prefix('/lessons')->controller(LessonController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/{lesson:global_position}', 'show')->name('api.lessons.show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store')->name('lessons.store');
                Route::get('/{lesson}/edit', 'edit')->whereNumber('lesson')->name('api.lessons.edit');
                Route::put('/{lesson}', 'update')->name('lessons.update');
                Route::patch('/{lesson}', 'update');
                Route::delete('/{lesson}', 'destroy')->name('lessons.destroy');
            });
        });

        Route::prefix('/speakers')->controller(SpeakerController::class)->group(function () {
            Route::post('/', 'store')->name('speaker.store');
            Route::get('/auth', 'show')->name('sound-booth.speaker');
            Route::get('/{speaker}/audios', 'getAudios')->name('api.speakers.fetch');
        });

        Route::prefix('/units')->controller(UnitController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/', 'index')->name('api.academy.units.index');
                Route::get('/{unit}', 'show')->name('api.units.show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store')->name('units.store');
                Route::get('/search', 'search')->name('api.units.search');
                Route::get('/{unit}/edit', 'edit')->whereNumber('unit')->name('api.units.edit');
                Route::put('/{unit}', 'update')->name('units.update');
                Route::patch('/{unit}', 'update');
                Route::delete('/{unit}', 'destroy')->name('units.destroy');
            });
        });

        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/decks', 'getDecks')->name('deck-master.get-decks');
            Route::get('/decks/{deck}/cards', 'getCards')->name('deck-master.get-cards');
            Route::post('/decks/{deck}/quiz', 'getQuiz')->name('deck-master.get-quiz');
        });

        Route::prefix('/sound-booth')->group(function () {
            Route::controller(SoundBoothController::class)->group(function () {
                Route::get('/get-speaker-options', 'getSpeakerOptions')->name('speaker.options');
                Route::post('/get-auto-items', 'getAutoItems')->name('sound-booth.get.auto');
                Route::post('/get-deck-items', 'getDeckItems')->name('sound-booth.get.deck');
            });

            Route::prefix('/stash')->controller(StashRecordController::class)->group(function () {
                Route::post('/store', 'store')->name('stash.store');
                Route::post('/upload', 'upload')->name('stash.upload');
                Route::delete('/{stashKey}', 'destroy')->name('stash.destroy');
                Route::delete('/clear/{speakerId}', 'clearStash')->name('stash.clear');
            });
        });

        Route::middleware(['student'])->group(function () {
            Route::prefix('/scores')->controller(ScoreController::class)->group(function () {
                Route::get('/', 'index')->name('api.academy.scores.index');
                Route::post('/', 'store')->name('scores.store');
                Route::get('/history/{scorable_type}/{scorable_id}', 'history')->name('api.academy.scores.history');
                Route::post('/purge', 'purge')->name('scores.purge');
                Route::delete('/{score}', 'destroy')->name('scores.destroy');
            });

            Route::prefix('/teachers')->controller(TeacherController::class)->group(function () {
                Route::post('/', 'store')->name('teachers.store');
                Route::patch('/{teacher}', 'update')->name('teachers.update');
                Route::delete('/{teacher}', 'destroy')->name('teachers.destroy');
            });

            Route::get('/academy/state', [AcademyStateController::class, 'show'])->name('api.academy.state');

            Route::prefix('/card-dealer')->controller(CardDealerController::class)->group(function () {
                Route::get('/', 'index')->name('api.card-dealer.index');
                Route::post('/get-scope-cards', 'getScopeCards')->name('api.card-dealer.get.cards');
                Route::get('/review', 'review')->name('api.card-dealer.review');
            });
        });

        Route::middleware(['admin'])->group(function () {
            Route::prefix('/admin')->group(function () {
                Route::post('/email', [EmailAnnouncementController::class, 'store'])->name('email.store');
                Route::get('/toggle-view/{role?}', [UserController::class, 'toggleView'])->name('admin.toggle-view');
            });

            Route::prefix('/word-logger')->controller(WordLoggerController::class)->group(function () {
                Route::get('/relatives', 'relatives')->name('word-logger.relatives');
                Route::get('/sentences', 'sentences')->name('word-logger.sentences');
                Route::get('/inflections', 'inflections')->name('word-logger.inflections');
                Route::get('/editor-data', 'editorData')->name('word-logger.editor-data');
            });

            Route::prefix('/speech-maker')->controller(SpeechMakerController::class)->group(function () {
                Route::get('/', 'index')->name('speech-maker.get');
                Route::get('/sentences/{id}/terms', function (string $sentenceId) {
                    return response()->json([
                        'terms' => app(SentenceService::class)
                            ->getSentenceTerms(Sentence::findOrFail($sentenceId))
                            ->toArray(),
                    ]);
                })->name('speech-maker.get-terms');
            });

            Route::prefix('/lesson-planner')->controller(LessonPlannerController::class)->group(function () {
                Route::get('/', 'index')->name('lesson-planner.get');
                Route::patch('/', 'update')->name('lesson-planner.update');
                Route::get('/units/{unit}/decks', 'unitDecks')->name('lesson-planner.unit-decks.get');
            });
        });
    });

    require __DIR__.'/auth.php';
});
