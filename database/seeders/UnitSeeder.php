<?php

namespace Database\Seeders;

use App\Models\Activity;
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
        Lesson::withoutEvents(function () {
            $deckIds = Deck::pluck('id')->toArray();

            Unit::factory()
                ->count(3)
                ->sequence(fn ($sequence) => [
                    'position' => $sequence->index + 1,
                    'title' => fake()->sentence(3),
                    'published' => fake()->boolean(),
                ])
                ->create()
                ->each(fn (Unit $unit) => Lesson::factory()
                    ->count(9)
                    ->for($unit)
                    ->sequence(fn ($sequence) => [
                        'group' => 'main',
                        'unit_position' => $sequence->index + 1,
                        'global_position' => $unit->position.'0'.($sequence->index + 1),
                        'title' => fake()->sentence(3),
                        'description' => fake()->paragraph(3),
                        'document' => [
                            'schemaVersion' => '1',
                            'skills' => [
                                [
                                    'type' => 'grammar',
                                    'title' => fake()->word(),
                                    'description' => fake()->text(100),
                                    'blocks' => []
                                ],
                                [
                                    'type' => 'vocab',
                                    'title' => fake()->word(),
                                    'description' => fake()->text(100),
                                    'blocks' => []
                                ],
                                [
                                    'type' => 'convo',
                                    'title' => fake()->word(),
                                    'description' => fake()->text(100),
                                    'blocks' => []
                                ],
                            ]
                        ],
                        'deck_id' => fake()->randomElement($deckIds),
                        'activity_id' => Activity::factory()
                            ->sequence(fn ($sequence) => [
                                'document' => [
                                    'schemaVersion' => '1',
                                    'blocks' => []
                                ]
                            ])
                            ->create()->id,
                        'dialog_id' => null,
                        'unlock_conditions' => null,
                        'published' => fake()->boolean()
                    ])
                    ->create()
                );
        });
    }
}
