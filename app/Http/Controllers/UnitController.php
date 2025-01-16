<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;
use App\Pages\Units\Unit01\Unit01;
use App\Pages\Units\Unit02\Unit02;
use App\Pages\Units\Unit03\Unit03;
use App\Policies\LessonPolicy;
use App\Traits\RedirectsToSubscribe;
use Illuminate\Support\Facades\View;

/**
 * This class allows you to access the user dashboard section
 */
class UnitController extends Controller
{
    use RedirectsToSubscribe;

    protected array $units = [
        'u1' => Unit01::class,
        'u2' => Unit02::class,
        'u3' => Unit03::class,
    ];

    public function __construct(protected LessonPolicy $can) {}

    /**
     * Renders a list of lessons a student can take. Redirects to subscription portal if not subscribed.
     */
    public function index(Request $request)
    {
        $auth = $request->user();

        View::share('pageTitle', 'Online Course for Palestinian Arabic with Lessons, Activities & Dialogues');
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewLessonIndex($auth));

            return view('lessons.index');
        });
    }

    public function unit(Request $request, $unit)
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

    public function lesson(Request $request, $unit, $lesson)
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
