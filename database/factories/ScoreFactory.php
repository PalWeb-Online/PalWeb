<?php

namespace Database\Factories;

use App\Models\Deck;
use App\Models\Score;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Score>
 */
class ScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'scorable_type' => null,
            'scorable_id' => null,
            'settings' => [
                'modelType' => 'deck',
                'typeInput' => false,
                'options' => [
                    'allGlosses' => false,
                    'anyGloss' => false
                ]
            ],
            'score' => 0,
            'results' => [],
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Score $score) {
            $deck = Deck::find($score->scorable_id);
            $results = [];

            foreach ($deck->terms as $term) {
                $answers = [$term->glosses->firstWhere('id', $term->pivot->gloss_id)->gloss];

                $isCorrect = fake()->boolean(50);
                $response = $isCorrect
                    ? fake()->randomElement($answers)
                    : fake()->word();

                $results[] = [
                    'term' => [
                        'term' => $term->term,
                        'slug' => $term->slug,
                    ],
                    'answer'   => $answers,
                    'response' => $response,
                    'correct'  => in_array($response, $answers, true),
                ];
            }

            $score->results = $results;

            $total = count($results);
            $correct = collect($results)->where('correct', true)->count();
            $score->score = round($correct / $total, 2);
        });
    }
}
