<?php

namespace Tests\Unit\Repositories;

use App\Enums\DictionaryCategory;
use App\Exceptions\InvalidCategoryException;
use App\Models\Category;
use App\Models\Pronunciation;
use App\Models\Term;
use App\Repositories\TermRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_getTermForCategoryAndSlug_throws_exception_if_not_a_valid_category()
    {
        $this->expectException(InvalidCategoryException::class);
        $this->getItem()->getTermForCategoryAndSlug('not-a-category', 'slug');
    }

    protected function getItem()
    {
        return new TermRepository;
    }

    public function test_getTermForCategoryAndSlug_with_all_possible_categories()
    {
        $data = $this->seedTermsAndRelations();

        foreach (DictionaryCategory::all() as $category) {
            $this->assertCategoryWorks($category, $data);
        }
    }

    protected function seedTermsAndRelations()
    {
        $categories = [];
        $terms = [];
        $pronunciations = [];

        foreach (DictionaryCategory::all() as $category) {
            $categories[$category] = Category::factory()->category($category)->create();
        }

        $nounTerm = Term::factory()->create([
            'category_id' => $categories['noun']->id,
        ]);

        $terms['noun'] = $nounTerm;

        foreach (DictionaryCategory::all() as $category) {
            $theTerm = Term::factory()->create([
                'category_id' => $categories[$category]->id,
                'term' => $nounTerm->term,
                'slug' => $nounTerm->slug,
            ]);

            $terms[$category] = $theTerm;

            $pronunciations[$category] = Pronunciation::factory()->create([
                'term_id' => $theTerm->id,
                'translit' => $theTerm->slug,
            ]);
        }

        // Should not show this term
        $terms['should-not-appear'] = Term::factory()->create([
            'category_id' => $categories['noun']->id,
        ]);

        return compact('terms', 'categories', 'pronunciations');
    }

    protected function assertCategoryWorks($category, $data)
    {
        $item = $this->getItem();
        $term = $data['terms'][$category];
        $result = $item->getTermForCategoryAndSlug($category, $term->slug);

        $this->assertIsClass(Term::class, $result->term);
        $this->assertEquals($term->category, $category);

        $this->assertEquals($term->id, $result->term->id);
        $this->assertEquals(9, count($result->related));
        foreach ($result->related as $related) {
            $this->assertIsClass(Term::class, $related);
            $this->assertNotEquals($term->id, $related->id);
        }
    }
}
