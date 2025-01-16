<?php

namespace Database\Factories;

use App\Models\Dialect;
use Illuminate\Database\Eloquent\Factories\Factory;

class PronunciationFactory extends Factory
{
    public function definition(): array
    {
        $translit = $this->faker->word();

        return [
            'translit' => $translit,
            'phonemic' => '/'.$translit.'/',
            'phonetic' => '['.$translit.']',
            'dialect_id' => Dialect::all()->random()->id,
            'borrowed' => $this->faker->boolean(),
        ];
    }
}
