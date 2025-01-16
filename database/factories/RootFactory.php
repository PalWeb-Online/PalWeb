<?php

namespace Database\Factories;

use App\Models\Root;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Root>
 */
class RootFactory extends Factory
{
    protected $model = Root::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $arabicLetters = [
            'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك',
            'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ء',
        ];

        $unique = false;
        $root = '';

        while (! $unique) {
            $randomLetters = $this->faker->randomElements($arabicLetters, 3);
            $root = collect($randomLetters)->implode('');

            if (Root::where('root', $root)->doesntExist()) {
                $unique = true;
            }
        }

        return [
            'root' => $root,
        ];
    }
}
