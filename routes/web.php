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
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/csrf-token', fn () => response()->json(['csrfToken' => csrf_token()]));
    Route::post('/language/{lang}', [LanguageController::class, 'store']);

    Route::prefix('/terms')->controller(TermController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/random', 'random');
        Route::get('/featured', 'showFeatured');
        Route::get('/{term:slug}', 'show');
        Route::get('/{term}/sentences/{gloss}', 'getSentences');
        Route::get('/{term}/pronunciations', 'getPronunciations');
        Route::post('/{term}/pin', 'pin')->middleware(['auth', 'verified']);

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store');
            Route::get('/{term}/edit', 'edit')->whereNumber('term');
            Route::put('/{term}', 'update');
            Route::patch('/{term}', 'update');
            Route::delete('/{term}', 'destroy');
        });
    });

    Route::get('/roots/{root}', [RootController::class, 'fetch']);
    Route::get('/decks/search', [DeckController::class, 'search']);

    Route::get('/pronunciations/{pronunciation}/audios', function (Pronunciation $pronunciation) {
        return AudioResource::collection($pronunciation->audios);
    });

    Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/random', 'random');
        Route::post('/many', 'getMany');
        Route::get('/{sentence}', 'show');
        Route::post('/{sentence}/pin', 'pin')->middleware(['auth', 'verified']);

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store');
            Route::get('/{sentence}/edit', 'edit')->whereNumber('sentence');
            Route::put('/{sentence}', 'update');
            Route::patch('/{sentence}', 'update');
            Route::delete('/{sentence}', 'destroy');
        });
    });

    Route::prefix('/search')->controller(SearchGenieController::class)->group(function () {
        Route::post('/', 'getResults');
        Route::get('/filter-options', 'getFilterOptions');
    });

    Route::prefix('/wiki')->controller(PageController::class)->group(function () {
        Route::get('/tree', 'getWikiTree');
        Route::get('/{page:slug}', 'show');

        Route::middleware(['auth', 'verified', 'admin'])->group(function () {
            Route::post('/', 'store');
            Route::get('/search', 'search');
            Route::get('/{page}/edit', 'edit')->whereNumber('page');
            Route::patch('/{page}', 'update');
            Route::delete('/{page}', 'destroy');
        });
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/community', [CommunityController::class, 'index']);

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
                Route::get('/{user:username}', 'show');
                Route::get('/{user}/edit', 'edit')->whereNumber('user');
                Route::patch('/{user}', 'update');

                Route::patch('/{user}/roles/toggle-student', 'toggleStudentRole')
                    ->middleware(['verified', 'admin']);
            });

            Route::controller(AvatarController::class)->group(function () {
                Route::get('/{user:username}/avatars', 'index');
                Route::post('/{user:username}/avatars', 'store');
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
            });
            Route::delete('/{avatar}', [AvatarController::class, 'destroy']);
        });

        Route::prefix('/push-subscriptions')->controller(PushSubscriptionController::class)
            ->group(function () {
                Route::post('/', 'store');
                Route::delete('/', 'destroy');
            });
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::prefix('/auth')->controller(UserController::class)->group(function () {
            Route::get('/decks', 'getDecks');
            Route::patch('/preferences', 'updatePreferences');
        });

        Route::prefix('/activities')->controller(ActivityController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/{activity}', 'show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store');
                Route::get('/{activity}/edit', 'edit')->whereNumber('activity');
                Route::put('/{activity}', 'update');
                Route::patch('/{activity}', 'update');
                Route::delete('/{activity}', 'destroy');
            });
        });

        Route::prefix('/audios')->controller(AudioController::class)->group(function () {
            Route::get('/', 'index');
            Route::delete('/{audio}', 'destroy');
        });

        Route::prefix('/cards')->controller(CardController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/purge', 'purge');

            Route::middleware('can:update,card')->group(function () {
                Route::post('/{card}', 'update');
                Route::post('/{card}/master', 'master');
                Route::post('/{card}/suspend', 'suspend');
                Route::post('/{card}/reset', 'reset');
                Route::delete('/{card}', 'destroy');
            });
        });

        Route::prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/random', 'random');
            Route::post('/', 'store');
            Route::get('/{deck}', 'show');
            Route::get('/{deck}/edit', 'edit');
            Route::put('/{deck}', 'update');
            Route::patch('/{deck}', 'update');
            Route::delete('/{deck}', 'destroy');
            Route::post('/{deck}/pin', 'pin');
            Route::post('/{deck}/copy', 'copy');
            Route::post('/{deck}/export', 'export');
            Route::post('/{deck}/terms/{term}/toggle', 'toggleTerm');
        });

        Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/', 'index')->middleware(['student']);
                Route::get('/{dialog}', 'show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store');
                Route::get('/search', 'search');
                Route::get('/{dialog}/edit', 'edit')->whereNumber('dialog');
                Route::put('/{dialog}', 'update');
                Route::patch('/{dialog}', 'update');
                Route::delete('/{dialog}', 'destroy');
            });
        });

        Route::prefix('/feedback')->controller(FeedbackCommentController::class)->group(function () {
            Route::post('/', 'store');

            Route::middleware(['admin'])->group(function () {
                Route::get('/', 'index');
                Route::delete('/{feedbackComment}', 'destroy');
            });
        });

        Route::prefix('/lessons')->controller(LessonController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/{lesson:global_position}', 'show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store');
                Route::get('/{lesson}/edit', 'edit')->whereNumber('lesson');
                Route::put('/{lesson}', 'update');
                Route::patch('/{lesson}', 'update');
                Route::delete('/{lesson}', 'destroy');
            });
        });

        Route::prefix('/speakers')->controller(SpeakerController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('/auth', 'show');
            Route::get('/{speaker}/audios', 'getAudios');
        });

        Route::prefix('/units')->controller(UnitController::class)->group(function () {
            Route::middleware(['student'])->group(function () {
                Route::get('/', 'index');
                Route::get('/{unit}', 'show');
            });

            Route::middleware(['admin'])->group(function () {
                Route::post('/', 'store');
                Route::get('/search', 'search');
                Route::get('/{unit}/edit', 'edit')->whereNumber('unit');
                Route::put('/{unit}', 'update');
                Route::patch('/{unit}', 'update');
                Route::delete('/{unit}', 'destroy');
            });
        });

        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/decks', 'getDecks');
            Route::get('/decks/{deck}/cards', 'getCards');
            Route::post('/decks/{deck}/quiz', 'getQuiz');
        });

        Route::prefix('/sound-booth')->group(function () {
            Route::controller(SoundBoothController::class)->group(function () {
                Route::get('/get-speaker-options', 'getSpeakerOptions');
                Route::post('/get-auto-items', 'getAutoItems');
                Route::post('/get-deck-items/{deckId}', 'getDeckItems');
            });

            Route::prefix('/stash')->controller(StashRecordController::class)->group(function () {
                Route::post('/store', 'store');
                Route::post('/upload', 'upload');
                Route::delete('/{stashKey}', 'destroy');
                Route::delete('/clear/{speakerId}', 'clearStash');
            });
        });

        Route::middleware(['student'])->group(function () {
            Route::prefix('/scores')->controller(ScoreController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('/history/{scorable_type}/{scorable_id}', 'history');
                Route::post('/purge', 'purge');
                Route::delete('/{score}', 'destroy');
            });

            Route::prefix('/teachers')->controller(TeacherController::class)->group(function () {
                Route::post('/', 'store');
                Route::patch('/{teacher}', 'update');
                Route::delete('/{teacher}', 'destroy');
            });

            Route::get('/academy/state', [AcademyStateController::class, 'show']);

            Route::prefix('/card-dealer')->controller(CardDealerController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/get-scope-cards', 'getScopeCards');
                Route::get('/review', 'review');
            });
        });

        Route::middleware(['admin'])->group(function () {
            Route::prefix('/admin')->group(function () {
                Route::post('/email', [EmailAnnouncementController::class, 'store']);
                Route::get('/toggle-view/{role?}', [UserController::class, 'toggleView']);
            });

            Route::prefix('/word-logger')->controller(WordLoggerController::class)->group(function () {
                Route::get('/relatives', 'relatives');
                Route::get('/sentences', 'sentences');
                Route::get('/inflections', 'inflections');
                Route::get('/editor-data', 'editorData');
            });

            Route::prefix('/speech-maker')->controller(SpeechMakerController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/sentences/{id}/terms', function (string $sentenceId) {
                    return response()->json([
                        'terms' => app(SentenceService::class)
                            ->getSentenceTerms(Sentence::findOrFail($sentenceId))
                            ->toArray(),
                    ]);
                });
            });

            Route::prefix('/lesson-planner')->controller(LessonPlannerController::class)->group(function () {
                Route::get('/', 'index');
                Route::patch('/', 'update');
                Route::get('/units/{unit}/decks', 'unitDecks');
            });
        });
    });

    require __DIR__.'/auth.php';
});
