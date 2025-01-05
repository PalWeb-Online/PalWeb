<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'qid' => Str::uuid(),
            'name_ar' => $this->faker->city,
            'name_en' => $this->faker->city,
            'coordinates' => DB::raw("POINT({$this->faker->latitude}, {$this->faker->longitude})"),
        ];
    }
}
