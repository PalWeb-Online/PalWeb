<?php

namespace App\Services;

use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Gloss;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class QuizService
{
    public function generateQuiz(Model $model, array $settings): array
    {
        if ($model instanceof Deck) {
            return $this->generateDeckQuiz($model, $settings);
        }

        if ($model instanceof Dialog) {
            return $this->generateDialogQuiz($model, $settings);
        }

        throw new InvalidArgumentException("Unsupported model type for quiz generation.");
    }

    private function generateDeckQuiz(Deck $deck, array $settings): array
    {

        $typeInput = $settings['typeInput'] ?? false;
        $allGlosses = $settings['options']['allGlosses'] ?? false;
        $anyGloss = $settings['options']['anyGloss'] ?? false;

        $quiz = [];

        foreach ($deck->terms as $term) {
            if ($typeInput) {
                $term->load(['inflections']);
                if ($term->inflections->isEmpty()) {
                    continue;
                }

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

                $answer = $anyGloss || !$glossId
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
        return $quiz;
    }

    private function generateDialogQuiz(Dialog $dialog, array $settings): array
    {
        // This is an example implementation. You may need to adjust it based on your Sentence model structure.
        // This example assumes Sentence model has 'body' and 'translation' attributes.
        $quiz = [];
        $dialog->load('sentences');

        if ($dialog->sentences->isEmpty()) {
            return [];
        }

        foreach ($dialog->sentences as $sentence) {
            // Making a multiple-choice quiz to match a sentence with its translation
            $answer = $sentence->translation;
            if (!$answer) continue;

            $decoys = $dialog->sentences->where('id', '!=', $sentence->id)->pluck('translation')->filter()->shuffle()->take(3);
            $options = $decoys->push($answer)->shuffle();

            $quiz[] = [
                'term' => ['term' => $sentence->body], // Using 'term' structure for frontend consistency
                'answer' => $options->search($answer),
                'options' => $options->all(),
                'response' => null,
                'correct' => false,
            ];
        }

        return $quiz;
    }
}
