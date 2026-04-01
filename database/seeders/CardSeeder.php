<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardReview;
use App\Models\Term;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = Term::all();

        foreach ($terms->random(min($terms->count(), 500)) as $term) {
            if (rand(1, 100) <= 20) {
                continue;
            }

            $bucket = rand(1, 100);
            $step = null;

            if ($bucket <= 20) {
                $repetitions = 0;
                $step = rand(0, 2);
                $interval = 0;
                $lastReviewed = now()->subHours(rand(0, 48));

            } elseif ($bucket <= 60) {
                $repetitions = rand(1, 3);
                $interval = rand(1, 7);
                $lastReviewed = now()->subDays(rand(0, $interval));

            } elseif ($bucket <= 90) {
                $repetitions = rand(4, 15);
                $interval = rand(8, 60);
                $lastReviewed = now()->subDays(rand(0, $interval));

            } else {
                $repetitions = rand(16, 40);
                $interval = rand(61, 365);
                $lastReviewed = now()->subDays(rand(0, $interval));
            }

            $dueAt = rand(1, 100) <= 30
                ? now()->subDays(rand(0, 5))
                : now()->addDays(rand(1, $interval ?: 1));

            if ($lastReviewed->isToday() && $dueAt->lessThanOrEqualTo(now())) {
                $dueAt = now()->addDays($interval ?: 1);
            }

            $card = Card::create([
                'user_id' => '1',
                'term_id' => $term->id,
                'repetitions' => $repetitions,
                'lapses' => rand(0, 3),
                'step' => $step,
                'interval_days' => $interval,
                'ease_factor' => rand(15, 30) / 10,
                'last_reviewed_at' => $lastReviewed,
                'due_at' => $dueAt,
                'created_at' => $step !== null ? $lastReviewed->copy() : $lastReviewed->copy()->subDays(rand(5, 30)),
            ]);

            $this->seedReviewHistory($card);
        }
    }

    protected function seedReviewHistory(Card $card): void
    {
        $stepCount = $card->step ?? 0;
        if ($card->repetitions > 0) {
            $stepCount = 2;
        }

        for ($i = 0; $i < $stepCount; $i++) {
            CardReview::create([
                'user_id' => $card->user_id,
                'card_id' => $card->id,
                'type' => 'learning',
                'grade' => 2,
                'seconds_spent' => rand(3, 15),
                'reviewed_at' => $card->step !== null ? $card->last_reviewed_at : $card->created_at,
            ]);
        }

        if ($card->repetitions > 0) {
            for ($i = 0; $i < $card->repetitions; $i++) {
                $daysAgo = $i === 0 ? 0 : $i * ($card->interval_days / $card->repetitions);

                CardReview::create([
                    'user_id' => $card->user_id,
                    'card_id' => $card->id,
                    'type' => 'review',
                    'grade' => 2,
                    'seconds_spent' => rand(2, 10),
                    'reviewed_at' => $card->last_reviewed_at->copy()->subDays($daysAgo),
                ]);
            }
        }
    }
}
