<?php

namespace App\Services;

use App\Http\Resources\TermResource;
use App\Models\Deck;
use App\Models\Gloss;
use App\Models\Term;

class QuizService
{
    public function generateQuiz(Deck $deck, array $settings): array
    {
        switch ($settings['quizType']) {
            case 'glosses':
                return $this->generateGlossesQuiz($deck, $settings);

            case 'inflections':
                return $this->generateInflectionsQuiz($deck, $settings);

            case 'sentences':
                return $this->generateSentencesQuiz($deck, $settings);
        }
    }

    private function generateGlossesQuiz(Deck $deck, array $settings): array
    {
        $strictTerms = $settings['options']['strictTerms'] ?? true;
        $strictGloss = $settings['options']['strictGloss'] ?? true;

        $quiz = [];

        foreach ($deck->terms->shuffle()->take(50) as $term) {
            $glossId = $term->pivot->gloss_id;

            $answer = $strictGloss && $glossId
                ? $term->glosses->firstWhere('id', $glossId)
                : $term->glosses->random();

            $decoysQuery = Gloss::query();

            if ($strictTerms) {
                $decoyGlossIds = $strictGloss
                    ? $deck->terms->pluck('pivot.gloss_id')->filter()
                    : $deck->terms->pluck('glosses')->flatten()->pluck('id');
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

    private function generateInflectionsQuiz(Deck $deck, array $settings): array
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

    private function generateSentencesQuiz(Deck $deck, array $settings): array
    {
        $strictGloss = $settings['options']['strictGloss'] ?? true;
        $withTranslation = $settings['options']['withTranslation'] ?? true;

        $quiz = [];


        foreach ($deck->terms->shuffle() as $term) {
            if (count($quiz) >= 25) {
                break;
            }

            $sentences = $strictGloss
                ? $term->sentences->filter(fn ($s) => $s->pivot?->gloss_id === $term->pivot?->gloss_id)
                : $term->sentences;

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


            if ($withTranslation) {
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
