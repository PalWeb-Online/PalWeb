<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Gloss;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizzerController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Academy/Quizzer/Index', [
            'section' => 'academy',
            'decks' => DeckResource::collection(Deck::whereHasBookmark($request->user())->with(['terms', 'scores'])->get()),
        ]);
    }

    public function deck(Deck $deck): \Inertia\Response | RedirectResponse
    {
        $deck?->load(['terms', 'scores']);

        if ($deck->terms->isEmpty()) {
            session()->flash('notification',
                ['type' => 'success', 'message' => __('You can\'t Quiz an empty Deck!')]);

            return to_route('quizzer.index');

        } else {
            return Inertia::render('Academy/Quizzer/Show', [
                'section' => 'academy',
                'model' => new DeckResource($deck),
                'modelType' => 'deck',
            ]);
        }
    }

    public function generateQuiz(Request $request, Deck $deck): JsonResponse
    {
        $typeInput = $request->boolean('settings.typeInput');
        $allGlosses = $request->boolean('settings.options.allGlosses');
        $anyGloss = $request->boolean('settings.options.anyGloss');

        $quiz = [];

        foreach ($deck->terms as $term) {
            if ($typeInput) {
                $term->load(['inflections']);
                if ($term->inflections->isEmpty()) continue;

                $inflection = $term->inflections->random();

                $quiz[] = [
                    'term' => new TermResource($term)->additional(['detail' => true]),
                    'answer' => $term->inflections->where('form', $inflection->form)->pluck('inflection')->toArray(),
                    'prompt' => $inflection->form,
                    'response' => null,
                    'correct' => false,
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

                $quiz[] = [
                    'term' => new TermResource($term)->additional(['detail' => true]),
                    'answer' => $answer->id,
                    'options' => $options,
                    'response' => null,
                    'correct' => false,
                ];
            }
        }

        return response()->json(['quiz' => $quiz]);
    }
}
