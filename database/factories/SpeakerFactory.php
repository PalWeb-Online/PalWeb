<?php

namespace Database\Factories;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dialect_id' => Dialect::inRandomOrder()->first()->id,
            'location_id' => Location::inRandomOrder()->first()->id,
            'fluency' => $this->faker->numberBetween(1, 5),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
        ];
    }
}
