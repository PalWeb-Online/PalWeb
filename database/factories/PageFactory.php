<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $slug = str($title)->slug()->toString();

        return [
            'slug' => $slug,
            'title' => $title,
            'summary' => $this->faker->optional()->sentence(),
            'document' => [
                'blocks' => [
                    [
                        'type' => 'heading',
                        'level' => 1,
                        'text' => $title,
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => $this->faker->paragraphs(3, true),
                    ],
                ],
            ],
            'status' => $this->faker->randomElement(['draft', 'published']),
            'locale' => $this->faker->randomElement(['en', 'ar']),
            'published_at' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'position' => $this->faker->numberBetween(0, 25),
            'parent_id' => null,
        ];
    }
}
