<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Pages\Units\Unit01\Unit01;
use App\Pages\Units\Unit02\Unit02;
use App\Pages\Units\Unit03\Unit03;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;

class UnitController extends Controller
{
    protected array $units = [
        'u1' => Unit01::class,
        'u2' => Unit02::class,
        'u3' => Unit03::class,
    ];

    public function index(): \Illuminate\View\View|RedirectResponse
    {
        View::share('pageTitle', 'Academy');
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return view('lessons.index');
    }

    public function unit($unit): \Illuminate\View\View|RedirectResponse
    {
        View::share('pageTitle', "Academy: Unit $unit");
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return view('lessons.unit', [
            'unit' => $unit,
            'terms' => Term::all(),
        ]);
    }

    public function lesson($unit, $lesson): \Illuminate\View\View|RedirectResponse
    {
        View::share('pageTitle', "Academy: Unit $unit - Lesson $lesson");
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        return view('lessons.lesson', [
            'unit' => $unit,
            'lesson' => $lesson,
            'terms' => Term::all(),
            'bodyBackground' => 'lesson-layout',
        ]);
    }

    public function showUnit($unit)
    {
        View::share('pageTitle', 'Unit '.substr($unit, 1).': Home');
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        $unit = new $this->units[$unit];

        return $unit->render();
    }

    public function showLesson($unit, $lesson)
    {
        View::share('pageTitle', 'Unit '.substr($unit, 1).': Lesson '.substr($lesson, 1));
        View::share('pageDescription',
            'It\'s time to Learn Palestinian Arabic with the most comprehensive online course ever, featuring a curriculum of expertly-paced lessons designed to teach real-life skills & never overwhelm. Boost your vocabulary with activities & practical word decks. Develop your fluency with immersive & realistic dialogues. Your journey to mastering Palestinian Arabic starts here!');

        $unit = new $this->units[$unit];

        return $unit->getLesson($lesson);
    }
}
