<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GlossFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gloss' => $this->faker->sentence(),
        ];
    }
}
