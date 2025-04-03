<?php

namespace Database\Factories;

use App\Models\Dialog;
use Illuminate\Database\Eloquent\Factories\Factory;

class DialogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(5, true),
            'description' => $this->faker->text(),
        ];
    }
}
