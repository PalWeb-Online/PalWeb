<?php

namespace Database\Factories;

use App\Models\Gloss;
use App\Models\Pronunciation;
use App\Models\Root;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermFactory extends Factory
{
    public function definition(): array
    {
        // Create root first
        $root = Root::factory()->create();

        $terms = [
            'مرحبا', 'سلام', 'حب', 'عالم', 'لغة', 'كتاب', 'شكرا', 'مدرسة', 'سيارة', 'بيت', 'شمس', 'قمر', 'نجمة',
            'بحر', 'جبل', 'غابة', 'صحراء', 'نهر', 'سماء', 'طائر', 'حيوان', 'ثقافة', 'تعليم', 'معرفة', 'فن', 'موسيقى',
            'رقص', 'سفر', 'صداقة', 'عائلة', 'سعادة', 'حزن', 'حب', 'مدينة', 'قرية', 'مطبخ', 'طعام', 'شراب', 'لعبة',
            'رياضة', 'العاب', 'أمان', 'حقوق', 'عدل', 'قانون', 'حكم', 'حكومة', 'سلطة', 'تاريخ', 'أدب', 'لغة', 'كتابة',
            'اقتصاد', 'شعر', 'تكنولوجيا', 'علوم', 'رياضيات', 'فيزياء', 'كيمياء', 'بيولوجيا', 'طب', 'رسم', 'تصوير',
            'فيلم', 'مسرح', 'رواية', 'فنون', 'دين', 'فلسفة', 'سياسة', 'مجتمع', 'ثقافة', 'هندسة', 'حاسب', 'انترنت',
            'تصميم', 'فوتوغرافيا', 'رقص', 'تمثيل', 'موسيقى', 'تاريخ', 'جغرافيا', 'رياضيات', 'فيزياء', 'فلك', 'طب',
            'عمارة', 'نبات', 'حيوان', 'ذرة', 'برمجة', 'إحصاء', 'دين', 'فكر', 'حقوق', 'اقتصاد', 'فلسفة', 'سياسة',
        ];

        $categories = [
            'verb', 'noun', 'adjective', 'adverb', 'numeral', 'preposition',
            'conjunction', 'determiner', 'particle', 'phrase', 'affix',
        ];

        return [
            'term' => $this->faker->randomElement($terms),
            'translit' => 'default',
            'category' => $this->faker->randomElement($categories),
            'slug' => $this->faker->unique()->randomNumber(),
            'root_id' => $root->id,
            'etymology' => ['type' => 'inherited', 'source' => null],
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Citrullus_lanatus_1904.jpg/1024px-Citrullus_lanatus_1904.jpg',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Term $term) {
            Pronunciation::factory(2)->create(['term_id' => $term->id]);
            Gloss::factory(2)->create(['term_id' => $term->id]);

            $term->load('pronunciations');

            $translit = $term->pronunciations[0]->translit;
            $slug = $term->category.'-'.$translit;

            $duplicates = Term::where([
                'category' => $term->category,
                'translit' => $translit,
            ]);
            $count = $duplicates->count();

            if ($count > 0) {
                foreach ($duplicates->get() as $i => $duplicate) {
                    $duplicate->update(['slug' => $duplicate->category.'-'.$duplicate->translit.'-'.($i + 1)]);
                }
                $slug .= '-'.$count + 1;
            }

            $term->translit = $translit;
            $term->slug = $slug;
            $term->save();
        });
    }
}
