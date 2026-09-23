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
        $promptTerm = $settings['options']['promptTerm'] ?? true;
        $strictTerms = $settings['options']['strictTerms'] ?? true;
        $strictGloss = $settings['options']['strictGloss'] ?? true;

        $quiz = [];

        $terms = $deck->terms()
            ->whereNotNull('deck_term.gloss_id')
            ->withItemData()
            ->when($promptTerm, fn ($q) => $q->whereHas('pronunciations.audios'))
            ->get()
            ->shuffle()
            ->values();

        $questionTerms = $strictGloss
            ? $terms
            : $terms->unique('id')->values();

        foreach ($questionTerms as $term) {
            if (count($quiz) >= 50) {
                break;
            }

            $glossId = $term->pivot->gloss_id;

            $answer = $strictGloss && $glossId
                ? $term->glosses->firstWhere('id', $glossId)
                : $term->glosses->random();

            if (! $answer) {
                continue;
            }

            $excludedGlossIds = $term->glosses
                ->pluck('id')
                ->push($answer->id)
                ->unique()
                ->values();

            $decoysQuery = Gloss::query();

            if ($strictTerms) {
                $decoyGlossIds = $strictGloss
                    ? $questionTerms->pluck('pivot.gloss_id')->filter()->unique()->values()
                    : $questionTerms->pluck('glosses')->flatten()->pluck('id')->unique()->values();

                $decoysQuery->whereIn('id', $decoyGlossIds->diff($excludedGlossIds)->values());
            }

            $decoys = $decoysQuery
                ->whereNotIn('id', $excludedGlossIds)
                ->whereNot('term_id', $term->id)
                ->inRandomOrder()
                ->take(2)
                ->get();

            $options = collect([$answer, ...$decoys])
                ->keyBy('id')
                ->map(fn ($g) => $g->gloss)
                ->toArray();

            $quiz[] = [
                'term' => new TermResource($term),
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
        $terms = $deck->terms()
            ->whereNotNull('deck_term.gloss_id')
            ->with('inflections')
            ->get()
            ->shuffle()
            ->unique('id')
            ->values();

        foreach ($terms as $term) {
            if (count($quiz) >= 50) {
                break;
            }

            $validInflections = $term->inflections->filter(function ($inflection) {
                return $inflection->form !== 'genitive';
            });

            if ($validInflections->isEmpty()) {
                continue;
            }

            $inflection = $validInflections->random();

            $quiz[] = [
                'term' => new TermResource($term),
                'prompt' => $inflection->form,
                'answer' => $term->inflections
                    ->where('form', $inflection->form)
                    ->pluck('inflection')
                    ->toArray(),
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
        $terms = $deck->terms()
            ->whereNotNull('deck_term.gloss_id')
            ->with('sentences')
            ->get()
            ->shuffle()
            ->values();

        $questionTerms = $strictGloss
            ? $terms
            : $terms->unique('id')->values();

        foreach ($questionTerms as $term) {
            if (count($quiz) >= 25) {
                break;
            }

            $sentences = $strictGloss
                ? $term->sentences->filter(fn ($s) => $s->pivot?->gloss_id === $term->pivot?->gloss_id)
                : $term->sentences;

            if ($sentences->isEmpty()) {
                continue;
            }

            $sentence = $sentences->random();

            $sentenceTerms = app(SentenceService::class)->getSentenceTerms($sentence);

            $excludedDecoys = Term::query()
                ->whereHas('relatives', fn ($q) => $q
                    ->where('type', 'variant')
                    ->where('relative_id', $term->id)
                )
                ->pluck('id')
                ->push($term->id);

            if ($withTranslation) {
                $decoys = $terms
                    ->unique('id')
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
