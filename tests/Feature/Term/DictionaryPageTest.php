<?php

namespace Tests\Feature\Term;

use App\Models\Term;
use Tests\TestCase;

class DictionaryPageTest extends TestCase
{
    public function test_each_term_page_loads_correctly()
    {
        $terms = Term::all();
        $failedTerms = [];

        foreach ($terms as $term) {
            try {
                $response = $this->get('/dictionary/terms/'.$term->slug);
                $response->assertStatus(200);
            } catch (\Exception $e) {
                $failedTerms[] = $term->slug;
            }
        }

        if (! empty($failedTerms)) {
            echo "The following terms returned errors:\n";
            foreach ($failedTerms as $failedTerm) {
                echo "'$failedTerm'\n";
            }

            $this->fail('Some terms returned errors. Check the list above.');
        }
    }
}
