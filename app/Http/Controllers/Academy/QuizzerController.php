<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Gloss;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizzerController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Academy/Quizzer/Index', [
            'section' => 'academy',
            'decks' => DeckResource::collection(Deck::whereHasBookmark($request->user())->with(['terms'])->get()),
        ]);
    }

    public function deck(Deck $deck)
    {
        $deck?->load(['terms']);

        if ($deck->terms->isEmpty()) {
            session()->flash('notification',
                ['type' => 'success', 'message' => __('You can\'t Quiz an empty Deck!')]);

            return to_route('quizzer.index');

        } else {
            return Inertia::render('Academy/Quizzer/Show', [
                'section' => 'academy',
                'quizType' => 'deck',
                'model' => new DeckResource($deck)
            ]);
        }
    }

    public function generateQuiz(Request $request, Deck $deck)
    {
        $allGlosses = $request->boolean('settings.allGlosses');
        $anyGloss = $request->boolean('settings.anyGloss');
        $typeInput = $request->boolean('settings.typeInput');

        $quiz = [
            'type' => $typeInput ? 'input' : 'select',
            'questions' => [],
        ];

        foreach ($deck->terms as $term) {
            if ($typeInput) {
                $term->load(['inflections']);
                if ($term->inflections->isEmpty()) continue;

                $inflection = $term->inflections->random();

                $quiz['questions'][] = [
                    'term' => new TermResource($term)->additional(['detail' => true]),
                    'answer' => $term->inflections->where('form', $inflection->form)->pluck('inflection')->toArray(),
                    'prompt' => $inflection->form,
                    'input' => null,
                ];

            } else {
                $glossId = $term->pivot->gloss_id;

                $answer = $anyGloss || ! $glossId
                    ? $term->glosses->first()
                    : $term->glosses->firstWhere('id', $glossId);

                $decoysQuery = Gloss::query();

                if (! $allGlosses) {
                    $decoyGlossIds = $anyGloss
                        ? $deck->terms->pluck('glosses')->flatten()->pluck('id')
                        : $deck->terms->pluck('pivot.gloss_id')->filter();
                    $decoysQuery->whereIn('id', $decoyGlossIds);
                }

                $decoys = $decoysQuery
                    ->whereNot('id', $answer->id)
                    ->whereNot('term_id', $answer->term_id)
                    ->inRandomOrder()
                    ->take(2)
                    ->get();

                $options = collect([$answer, ...$decoys])->keyBy('id')->map(fn ($g) => $g->gloss)->toArray();

                $quiz['questions'][] = [
                    'term' => new TermResource($term)->additional(['detail' => true]),
                    'answer' => $answer->id,
                    'options' => $options,
                    'selection' => null,
                ];
            }
        }

        return response()->json(['quiz' => $quiz]);
    }
}
