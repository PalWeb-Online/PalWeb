<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::factory()
            ->count(27)
            ->create()
            ->each(function (Activity $activity) {
                Exercise::factory()
                    ->count(10)
                    ->for($activity)
                    ->sequence(function ($sequence) use ($activity) {
                        $options = [
                            1 => fake()->word(),
                            2 => fake()->word(),
                            3 => fake()->word(),
                        ];

                        return [
                            'prompt' => fake()->sentence(),
                            'options' => $options,
                            'answer' => array_rand($options),
                            'position' => $sequence->index + 1,
                        ];
                    })
                    ->create();
            });
    }
}
