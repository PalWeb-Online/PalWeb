<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Lesson;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::factory()
            ->count(9)
            ->sequence(fn ($sequence) => [
                'position' => $sequence->index + 1,
                'title' => fake()->sentence(3),
            ])
            ->create()
            ->each(fn (Unit $unit) => Lesson::factory()
                ->count(9)
                ->for($unit)
                ->sequence(fn ($sequence) => [
                    'position' => $sequence->index + 1,
                    'slug' => $unit->position.'0'.($sequence->index + 1),
                    'title' => fake()->sentence(3),
                    'skills' => [
                        [
                            'type' => 'grammar',
                            'title' => fake()->word(),
                            'description' => fake()->text(100),
                        ],
                        [
                            'type' => 'vocab',
                            'title' => fake()->word(),
                            'description' => fake()->text(100),
                        ],
                        [
                            'type' => 'convo',
                            'title' => fake()->word(),
                            'description' => fake()->text(100),
                        ],
                    ],
                    'deck_id' => Deck::pluck('id')->random(),
                    'dialog_id' => Dialog::pluck('id')->random(),
                ])
                ->create()
            );
    }
}
