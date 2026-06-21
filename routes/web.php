<?php

use App\Http\Controllers\Academy\ActivityController;
use App\Http\Controllers\Academy\DialogController;
use App\Http\Controllers\Academy\LessonController;
use App\Http\Controllers\Academy\ScoreController;
use App\Http\Controllers\Academy\UnitController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\EmailAnnouncementController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FeedbackCommentController;
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
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Http\Resources\UserShowResource;
use App\Http\Resources\UserResource;
use App\Models\Audio;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Page;
use App\Models\Pronunciation;
use App\Models\Sentence;
use App\Models\Term;
use App\Models\User;
use App\Services\SentenceService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/offline', function () {
    return Inertia::render('Error', ['status' => 911]);
})->name('offline');

Route::get('/', function () {
    $counts = Cache::remember('public_model_counts', now()->addHour(), function () {
        return [
            'terms' => Term::count(),
            'sentences' => Sentence::count(),
            'users' => User::count(),
            'decks' => Deck::where('private', false)->count(),
            'dialogs' => Dialog::count(),
            'audios' => Audio::count(),
        ];
    });

    $isStaging = app()->environment('staging');

    $users = !$isStaging
        ? User::whereKey([7, 10, 11, 18, 19, 878, 1113, 1115, 1186, 1224])->get()
        : User::inRandomOrder()->limit(10)->get();

    $decks = !$isStaging
        ? Deck::with('terms')->whereKey([2, 3, 4, 12, 19, 83, 100, 118])->get()
        : Deck::with('terms')
            ->where('private', false)
            ->inRandomOrder()
            ->limit(8)
            ->get();

    $sentences = !$isStaging
        ? Sentence::whereKey([256, 66, 54])->get()
        : Sentence::inRandomOrder()->limit(3)->get();

    $testimonialComments = [
        'PalWeb has made it so much easier to connect with real spoken Arabic. The dictionary and example sentences help me sound natural, not just textbook-correct.',
        'Finally — a resource that respects the richness of Palestinian Arabic and makes it accessible to learners. My students love the interactive decks and real-life examples.',
        'Recording audio for PalWeb has been a powerful way to share my dialect and support learners around the world. It’s exciting to be part of something that preserves our language.',
        'PalWeb stands out as a resource because of its content & the structuring of vocabulary on the site, where you can break down sentences into their constituent words and even words into their dictionary form. This is a format that more language sites should seek to emulate.',
        'I\'m learning Palestinian Arabic to connect better with my family, and PalWeb has been a lifesaver. I love that I can hear everything spoken out loud!',
    ];

    $testimonialUsers = !$isStaging
        ? User::whereKey([243, 1317, 16, 18, 3])->get()
        : User::inRandomOrder()->limit(count($testimonialComments))->get();

    $testimonials = collect($testimonialComments)
        ->map(fn (string $comment, int $index) => [
            'user' => new UserResource(
                $testimonialUsers->values()->get($index) ?? User::first()
            ),
            'comment' => $comment,
        ])
        ->all();

    return Inertia::render('Home', [
        'count' => $counts,
        'users' => UserResource::collection($users),
        'decks' => DeckResource::collection($decks),
        'sentences' => SentenceResource::collection($sentences),
        'testimonials' => $testimonials,
        'featuredTerm' => Term::find(662) ? new TermResource(Term::find(662))->additional(['detail' => true]) : null,
        'featuredUser' => User::find(1) ? new UserShowResource(User::find(1)->load(['dialect'])) : null,
        'featuredDeck' => Deck::find(2) ? new DeckResource(Deck::find(2)->load(['terms'])) : null,
    ]);
})->name('homepage');

Route::prefix('/wiki')->controller(PageController::class)->group(function () {
    Route::get('/', function () {
        return Page::where('slug', 'about')->exists()
            ? to_route('wiki.show', 'about')
            : to_route('wiki.edit');
    })->name('wiki.index');

    Route::middleware('admin')->group(function () {
        Route::get('/edit/{page?}', 'edit')->name('wiki.edit');
        Route::post('/', 'store')->name('wiki.store');
        Route::patch('/{page}', 'update')->name('wiki.update');
        Route::delete('/{page}', 'destroy')->name('wiki.destroy');
    });

    Route::get('/{page:slug}', 'show')->name('wiki.show');
});

Route::get('/coming-soon', function () {
    return Inertia::render('ComingSoon');
})->name('coming-soon');

Route::get('/subscription', function () {
    return Inertia::render('Auth/Subscription', [
        'section' => 'account',
    ]);
})->name('subscription.index');

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
        Route::get('/', 'index')->name('terms.index');
        Route::get('/{term:slug}', 'show')->name('terms.show');
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
        Route::get('/', 'index')->name('sentences.index');
        Route::get('/{sentence}', 'show')->name('sentences.show');
        Route::post('/{sentence}/pin', 'pin')->middleware(['auth', 'verified'])->name('sentences.pin');
        Route::post('/get-many', 'getMany')->name('sentences.get-many');
    });

    Route::prefix('/random')->group(function () {
        Route::get('/term', function () {
            return to_route('terms.show', Term::inRandomOrder()->first());
        })->name('terms.random');

        Route::get('/sentence', function () {
            return to_route('sentences.show', Sentence::inRandomOrder()->first());
        })->name('sentences.random');
    });

    Route::get('/roots/{root}', [RootController::class, 'show'])->name('roots.show');
});

Route::middleware(['auth'])->prefix('/hub')->group(function () {
    Route::patch('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::prefix('/users')->group(function () {
        Route::get('/', [CommunityController::class, 'index'])->name('users.index');
        Route::get('/{user:username}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user:username}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{user:username}', [UserController::class, 'update'])->name('users.update');
        Route::get('/{user:username}/avatars', [AvatarController::class, 'index'])->name('users.avatars.index');
        Route::post('/{user:username}/avatars', [AvatarController::class, 'store'])->name('users.avatars.store');
        Route::post('/{user:username}/teacher', [TeacherController::class, 'store'])->name('users.teacher.store');
    });

    Route::delete('/avatars/{avatar}', [AvatarController::class, 'destroy'])->name('avatars.destroy');

    Route::get('/avatars/get', function () {
        $avatars = File::files(public_path('img/avatars'));

        return array_map(function ($file) {
            return [
                'url' => asset('img/avatars/' . $file->getFilename()),
                'filename' => $file->getFilename(),
            ];
        }, $avatars);
    })->name('avatars.get');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/academy')->middleware(['student'])->group(function () {
        Route::controller(UnitController::class)->group(function () {
            Route::get('/units', 'index')->name('units.index');
            Route::get('/units/{unit:position}', 'show')->name('units.show');
        });

        Route::controller(LessonController::class)->group(function () {
            Route::get('/lessons/{lesson:global_position}', 'show')->name('lessons.show');
        });

        Route::controller(ActivityController::class)->group(function () {
            Route::get('/activities/{activity}', 'show')->name('activities.show');
            Route::get('/activities/{activity}/activity', 'activity')->name('activities.activity');
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

        Route::prefix('/scores')->controller(ScoreController::class)->group(function () {
            Route::get('/', 'index')->name('scores.index');
            Route::post('/', 'store')->name('scores.store');
            Route::get('/{scorable_type}/{scorable_id}', 'history')->name('scores.history');
            Route::post('/purge', 'purge')->name('scores.purge');
            Route::delete('/{score}', 'destroy')->name('scores.destroy');
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

        Route::get('/random/deck', function () {
            return to_route('decks.show', Deck::inRandomOrder()->first());
        })->name('decks.random');
    });

    Route::prefix('/workbench')->group(function () {
        Route::prefix('/deck-master')->controller(DeckMasterController::class)->group(function () {
            Route::get('/', 'index')->name('deck-master.index');
            Route::get('/get-decks', 'getDecks')->name('deck-master.get-decks');
            Route::get('/build/{deck?}', 'build')->name('deck-master.build');
            Route::get('/study/{deck}', 'study')->name('deck-master.study');
            Route::get('/study/{deck}/getCards', 'getCards')->name('deck-master.get-cards');
            Route::post('/study/{deck}/getQuiz', 'getQuiz')->name('deck-master.get-quiz');
        });

        Route::prefix('/card-dealer')->middleware(['student'])->controller(CardDealerController::class)->group(function () {
            Route::get('/', 'index')->name('card-dealer.index');
            Route::get('/cards', 'cards')->name('card-dealer.cards');
            Route::get('/review', 'review')->name('card-dealer.review');
            Route::post('/get/cards', 'getScopeCards')->name('card-dealer.get.cards');
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
                Route::get('/', 'index')->name('sound-booth.index');
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
            Route::get('/', 'index')->name('word-logger.index');
            Route::get('/term/{term?}', 'term')->name('word-logger.term');
        });

        Route::prefix('/speech-maker')->controller(SpeechMakerController::class)->group(function () {
            Route::get('/', 'index')->name('speech-maker.index');
            Route::get('/dialog/{dialog?}', 'dialog')->name('speech-maker.dialog');
            Route::get('/dialog/{dialog}/sentence', 'dialogSentence')->name('speech-maker.dialog-sentence');
            Route::get('/sentence/{sentence?}', 'sentence')->name('speech-maker.sentence');
            Route::get('/get-terms/{id}', function (string $sentenceId) {
                return response()->json(['terms' => app(SentenceService::class)->getSentenceTerms(Sentence::findOrFail($sentenceId))->toArray()]);
            })->name('speech-maker.get-terms');
        });

        Route::prefix('/lesson-planner')->controller(LessonPlannerController::class)->group(function () {
            Route::get('/', 'index')->name('lesson-planner.index');
            Route::patch('/', 'update')->name('lesson-planner.update');
            Route::get('/unit/{unit?}', 'unit')->name('lesson-planner.unit');
            Route::get('/unit/{unit}/lesson', 'unitLesson')->name('lesson-planner.unit-lesson');
            Route::get('/lesson/{lesson?}', 'lesson')->name('lesson-planner.lesson');
            Route::get('/lesson/{lesson}/activity', 'lessonActivity')->name('lesson-planner.lesson-activity');
        });

        Route::prefix('/feedback')->controller(FeedbackCommentController::class)->group(function () {
            Route::get('/', 'index')->name('feedback.index');
            Route::post('/', 'store')->name('todo.store');
            Route::delete('/{feedbackComment}', 'destroy')->name('todo.destroy');
        });
    });

    Route::get('/get-decks', [UserController::class, 'getDecks'])->name('users.decks.get');
    Route::patch('/update-preferences', [UserController::class, 'updatePreferences'])->name('users.preferences.update');
    Route::get('/toggle-view/{role?}', [UserController::class, 'toggleView'])->name('admin.toggle-view');
});

Route::prefix('/api')->group(function () {
    Route::prefix('/users')->controller(UserController::class)->group(function () {
        Route::get('/{user:username}', 'fetch')->name('api.users.fetch');
        Route::patch('/{user}/roles/toggle-student', 'toggleStudentRole')->name('api.users.roles.toggleStudent');
    });

    Route::prefix('/activities')->controller(ActivityController::class)->group(function () {
        Route::get('/{activity}', 'fetch')->name('api.activities.fetch');
    });

    Route::prefix('/decks')->controller(DeckController::class)->group(function () {
        Route::get('/search', 'search')->name('api.decks.search');
    });

    Route::prefix('/dialogs')->controller(DialogController::class)->group(function () {
        Route::get('/search', 'search')->name('api.dialogs.search');
    });

    Route::prefix('/lessons')->controller(LessonController::class)->group(function () {
        Route::get('/{lesson}', 'fetch')->name('api.lessons.fetch');
    });

    Route::prefix('/units')->controller(UnitController::class)->group(function () {
        Route::get('/{unit}', 'fetch')->name('api.units.fetch');
        Route::get('/search', 'search')->name('api.units.search');
    });

    Route::prefix('/wiki')->controller(PageController::class)->group(function () {
        Route::get('/tree', 'getWikiTree')->name('api.wiki.tree');
        Route::get('/search', 'search')->name('api.wiki.search');
        Route::get('/{page}', 'fetch')->name('api.wiki.fetch');
    });

    // -------------------------------------------------------------------------
    // Library API Routes
    // -------------------------------------------------------------------------

    Route::prefix('/library')->group(function () {
        Route::prefix('/terms')->controller(TermController::class)->group(function () {
            Route::get('/', 'apiIndex')->name('api.terms.index');
            Route::get('/{term:slug}', 'apiShow')->name('api.terms.show');
        });
        Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
            Route::get('/', 'apiIndex')->name('api.sentences.index');
            Route::get('/{sentence}', 'apiShow')->name('api.sentences.show');
        });
        Route::prefix('/audios')->group(function () {
            Route::get('/', [AudioController::class, 'apiIndex'])->name('api.audios.index');
            Route::get('/{speaker}', [SpeakerController::class, 'apiShow'])->name('api.speaker.show');
        });
        Route::middleware('auth')->prefix('/decks')->controller(DeckController::class)->group(function () {
            Route::get('/', 'apiIndex')->name('api.decks.index');
            Route::get('/{deck}', 'apiShow')->name('api.decks.show');
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
