<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Models\Deck;
use App\Models\Gloss;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizzerController extends Controller
{
    public function index()
    {
        return Inertia::render('Academy/Quizzer/Index', [
            'section' => 'academy',
            'latest' => DeckResource::collection(Deck::inRandomOrder()->with(['terms'])->take(5)->get()),
        ]);
    }

    public function deck(Deck $deck)
    {
        $deck?->load(['terms']);

        return Inertia::render('Academy/Quizzer/Show', [
            'section' => 'academy',
            'quizType' => 'deck',
            'model' => new DeckResource($deck)
        ]);
    }

    public function generateQuiz(Request $request, Deck $deck)
    {
        $settings = $request->query('settings');

        $quiz = [];

        foreach ($deck->terms as $index => $term) {
//            todo: don't take a random gloss but rather the one indicated in the pivot table
            $answer = $term->glosses->random();

            $decoysQuery = $settings['allGlosses'] === true
                ? Gloss::query()
                : Gloss::whereIn('id', $deck->terms->pluck('glosses')->flatten()->pluck('id'));

            $decoys = $decoysQuery
                ->whereNot('id', $answer->id)
                ->inRandomOrder()
                ->take(2)
                ->get();

            $options = collect([$answer, ...$decoys])->keyBy('id')->map(fn($g) => $g->gloss)->toArray();

            $quiz[$index] = [
                'prompt' => $term->term,
                'answer' => $answer->id,
                'options' => $options,
                'selection' => null,
            ];
        }

        return response()->json(['quiz' => $quiz]);
    }
}
