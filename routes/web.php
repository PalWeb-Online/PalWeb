<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\EmailAnnouncementController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Route;

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
    return view('index');
})->middleware('pageTitle:Home')->name('homepage');

/**
 * Prompts an unauthenticated user to log in.
 */
Route::get('/unauth', function () {
    return view('unauth', [
        'bodyBackground' => 'hero-yellow'
    ]);
})->middleware('pageTitle:Access Denied')->name('unauth');

/**
 * Sets the application language for this user.
 */
Route::post("/lang/{lang}", [LanguageController::class, 'change'])->name("language.change");

/**
 * Email Routes
 */
Route::prefix('/email')->middleware('auth')->group(function () {
    Route::controller(EmailVerificationController::class)->group(function () {
        Route::get('/verification', 'prompt')->name('verification.notice');
        Route::get('/verification/{id}/{hash}', 'verify')->middleware([
            'signed', 'throttle:6,1'
        ])->name('verification.verify');
        Route::post('/verification/link', 'link')->middleware('throttle:6,1')->name('verification.send');
    });

    Route::controller(EmailAnnouncementController::class)->middleware('admin')->group(function () {
        Route::get('/compose', 'compose')->name('email.compose');
        Route::post('/send', 'send')->name('email.send');
    });
});

Route::prefix('/dictionary')->controller(TermController::class)->group(function () {
    Route::post('/search', 'search')->name('dictionary.search');
    Route::get('/random', 'random')->name('terms.random');
    Route::get('/todo', 'todo')->middleware('admin')->name('terms.todo');

    Route::prefix('/terms')->group(function () {
        Route::get('/', 'index')->name('terms.index');
        Route::get('/{term:slug}', 'show')->name('terms.show');

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
});

/*
 * Community Routes
 */
Route::prefix('/community')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {
        return view('community.index');
    })->middleware('pageTitle:Community')->name('community.index');

    // User Routes
    Route::get('users/{user:username}', [UserController::class, 'show'])->name('users.show');

    // Deck Routes
    Route::resource('/decks', DeckController::class);

    Route::prefix('/decks')->controller(DeckController::class)->group(function () {
        Route::get('/{deck}/get', 'get')->name('decks.get');
        Route::post('/{deck}/pin', 'pin')->name('decks.pin');
        Route::post('/{deck}/copy', 'copy')->name('decks.copy');
        Route::post('/{deck}/export', 'export')->name('decks.export');
        Route::post('/{deck}/toggle/{term}', 'toggleTerm')->name('decks.term.toggle');
        Route::patch('/{deck}/privacy', 'togglePrivacy')->name('decks.privacy.toggle');
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

    Route::prefix('/dictionary')->group(function () {

        Route::get('/request', function () {
            return view('terms.request');
        })->middleware('pageTitle:Request Term')->name('terms.request');

        Route::post('/request', [TermController::class, 'request'])->name('request.store');

        Route::resource('/sentences', SentenceController::class)->except(['index', 'show'])->middleware('admin');

        Route::prefix('/sentences')->controller(SentenceController::class)->group(function () {
            Route::get('/', 'index')->name('sentences.index');
            Route::get('/{sentence}', 'show')->name('sentences.show');
            Route::post('/{sentence}/pin', 'pin')->name('sentences.pin');
            Route::get('/todo', 'todo')->name('sentences.todo');
        });

        Route::prefix('/explore')->controller(ExploreController::class)->group(function () {
            Route::get('/', 'index')->name('explore.index');
            Route::get('/{page}', 'show')->name('explore.show');
        });
    });

    Route::prefix('/units')->controller(UnitController::class)->group(function () {
//        Route::get('/{unit}/lessons/{lesson}', 'showLesson')->name('lessons.lesson');
    });

    /**
     * Academy Routes
     */

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

    /**
     * Dashboard Routes
     */
    Route::prefix('/dashboard')->group(function () {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/workbench', 'workbench')->name('dashboard.workbench');
            Route::get('/subscription', 'subscription')->name('dashboard.subscription');
        });

        Route::prefix('/settings')->controller(UserSettingsController::class)->group(function () {
            Route::get('/change-profile', 'edit')->name('settings.profile.edit');
            Route::patch('/change-profile', 'update')->name('settings.profile.update');
            Route::get('/change-password', 'editPassword')->name('settings.password.edit');
            Route::patch('/change-password', 'updatePassword')->name('settings.password.update');
            Route::get('/change-avatar', 'editAvatar')->name('settings.avatar.edit');
            Route::patch('/change-avatar', 'updateAvatar')->name('settings.avatar.update');
            Route::patch('/toggle-privacy', 'togglePrivacy')->name('settings.privacy.toggle');
        });

    });
});

//Route::get('sitemap', function () {
//    $sitemap = Sitemap::create()->add(Url::create('/'))->add(Url::create('/dictionary'))->add(Url::create('/units'))->add(Url::create('/texts'))->add(Url::create('/docs'));
//
//    $terms = DB::table('terms')->join('categories', 'terms.category_id', '=', 'categories.id')->select('terms.*',
//        'categories.category')->get();
//
//    foreach ($terms as $term) {
//        $sitemap->add(Url::create("/dictionary/$term->category/$term->translit"));
//    }
//
//    $sitemap->writeToFile(public_path('sitemap.xml'));
//
//    return 'Sitemap created succesfully!';
//});
//
//Route::get('/flashcard', function () {
//    return view('components.flashcard-demo', ['term' => Term::firstWhere('translit', 'b-')]);
//})->name("flashcard");
//
//Route::get('/screenshot', function () {
//    return view('components.screenshot');
//});
//
//Route::get('/cv', function () {
//    return view('cv');
//})->name("cv");

require __DIR__.'/auth.php';
