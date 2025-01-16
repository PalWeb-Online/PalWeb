<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deck>
 */
class DeckFactory extends Factory
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
            'name' => $this->faker->words(5, true), // Generate a random name
            'description' => $this->faker->sentence(), // Generate a random description
            'private' => $this->faker->boolean(), // Randomly set private or public
        ];
    }
}
