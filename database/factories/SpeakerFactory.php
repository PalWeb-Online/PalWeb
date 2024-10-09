<?php

namespace Database\Factories;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    protected $model = Speaker::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'dialect_id' => Dialect::inRandomOrder()->first()->id,
            'location_id' => Location::inRandomOrder()->first()->id,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
        ];
    }
}
