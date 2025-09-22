<?php

namespace App\Services;

use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Gloss;
use App\Models\Term;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class QuizService
{
    public function generateQuiz(Model $model, array $settings): array
    {
        if ($model instanceof Deck) {
            switch ($settings['quizType']) {
                case 'term-gloss':
                    return $this->generateGlossSelectQuiz($model, $settings);

                case 'term-inflection':
                    return $this->generateInflectionInputQuiz($model, $settings);

                case 'sentence-term':
                    return $this->generateTermSelectQuiz($model, $settings);
            }
        }

        throw new InvalidArgumentException("Unsupported model type for quiz generation.");
    }

    private function generateGlossSelectQuiz(Deck $deck, array $settings): array
    {
        $allGlosses = $settings['options']['allGlosses'] ?? false;
        $anyGloss = $settings['options']['anyGloss'] ?? false;

        $quiz = [];

        foreach ($deck->terms->shuffle()->take(50) as $term) {
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

        return $quiz;
    }

    private function generateInflectionInputQuiz(Deck $deck, array $settings): array
    {
        $quiz = [];

        foreach ($deck->terms->shuffle()->take(50) as $term) {
            $term->load(['inflections']);
            if ($term->inflections->isEmpty()) {
                continue;
            }

            $inflection = $term->inflections->random();

            $quiz[] = [
                'term' => new TermResource($term)->additional(['detail' => true]),
                'prompt' => $inflection->form,
                'answer' => $term->inflections->where('form',
                    $inflection->form)->pluck('inflection')->toArray(),
                'response' => null,
                'correct' => false,
            ];
        }

        return $quiz;
    }

    private function generateTermSelectQuiz(Deck $deck, array $settings): array
    {
        $anyGloss = $settings['options']['anyGloss'] ?? false;
        $withPrompt = $settings['options']['withPrompt'] ?? true;

        $quiz = [];


        foreach ($deck->terms->shuffle() as $term) {
            if (count($quiz) >= 25) {
                break;
            }

            $sentences = $anyGloss
                ? $term->sentences
                : $term->sentences->filter(fn ($s) => $s->pivot?->gloss_id === $term->pivot?->gloss_id);

            if ($sentences->isEmpty()) {
                continue;
            }

            $sentence = $term->sentences->random();

            $sentenceTerms = collect($sentence->getTerms());

            $excludedDecoys = Term::query()
                ->whereHas('relatives', fn ($q) => $q
                    ->where('type', 'variant')
                    ->where('relative_id', $term->id)
                )
                ->pluck('id')
                ->push($term->id);


            if ($withPrompt) {
                $decoys = $deck->terms
                    ->whereNotIn('id', $excludedDecoys)
                    ->shuffle()
                    ->take(2);

            } else {
                $decoys = Term::query()
                    ->whereNotIn('id', $excludedDecoys)
                    ->where('category', '!=', $term->category)
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }

            $options = collect([$term, ...$decoys])
                ->keyBy('id')
                ->map(fn ($t) => [
                    'term' => $t['term'],
                    'translit' => $t['translit'],
                ])
                ->toArray();

            $sentence->setRelation('terms',
                $sentenceTerms->map(fn ($t) => (isset($t['id']) && $t['id'] === $term->id)
                    ? [
                        'sentencePivot' => [
                            'sent_term' => null,
                            'sent_translit' => null,
                        ],
                    ]
                    : $t)
            );

            $prompt = $sentence->terms
                ->map(fn ($t) => $t['sentencePivot']['sent_term'] ?? 'ــــــــ')
                ->implode(' ');

            $quiz[] = [
                'term' => new TermResource($term),
                'sentence' => $sentence,
                'prompt' => $prompt,
                'answer' => $term->id,
                'options' => $options,
                'response' => null,
                'correct' => false,
            ];
        }

        return $quiz;
    }
}
