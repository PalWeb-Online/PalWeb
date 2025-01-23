<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeckFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => $this->faker->words(5, true),
            'description' => $this->faker->sentence(),
            'private' => $this->faker->boolean(),
        ];
    }
}
