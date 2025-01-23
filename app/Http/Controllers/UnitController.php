<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Pages\Units\Unit01\Unit01;
use App\Pages\Units\Unit02\Unit02;
use App\Pages\Units\Unit03\Unit03;
use App\Policies\LessonPolicy;
use App\Traits\RedirectsToSubscribe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UnitController extends Controller
{
    use RedirectsToSubscribe;

    protected array $units = [
        'u1' => Unit01::class,
        'u2' => Unit02::class,
        'u3' => Unit03::class,
    ];

    public function __construct(protected LessonPolicy $can) {}

    public function index(Request $request): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Academy');
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewLessonIndex($auth));

            return view('lessons.index');
        });
    }

    public function unit(Request $request, $unit): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', "Academy: Unit $unit");
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $unit) {
            $this->failIfFalse($this->can->viewLesson($auth));

            return view('lessons.unit', [
                'unit' => $unit,
                'terms' => Term::all(),
            ]);
        });
    }

    public function lesson(Request $request, $unit, $lesson): \Illuminate\View\View | RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', "Academy: Unit $unit - Lesson $lesson");
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $unit, $lesson) {
            $this->failIfFalse($this->can->viewLesson($auth));

            return view('lessons.lesson', [
                'unit' => $unit,
                'lesson' => $lesson,
                'terms' => Term::all(),
                'bodyBackground' => 'lesson-layout',
            ]);
        });
    }

    public function showUnit(Request $request, $unit)
    {
        $auth = $request->user();

        View::share('pageTitle', 'Unit '.substr($unit, 1).': Home');
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $unit) {
            $this->failIfFalse($this->can->viewLesson($auth));

            $unit = new $this->units[$unit];

            return $unit->render();
        });
    }

    public function showLesson(Request $request, $unit, $lesson)
    {
        $auth = $request->user();

        View::share('pageTitle', 'Unit '.substr($unit, 1).': Lesson '.substr($lesson, 1));
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $unit, $lesson) {
            $this->failIfFalse($this->can->viewLesson($auth));

            $unit = new $this->units[$unit];

            return $unit->getLesson($lesson);
        });
    }
}
