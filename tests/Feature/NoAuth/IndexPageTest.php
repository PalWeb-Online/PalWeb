<?php

namespace Tests\Feature\NoAuth;

use Tests\TestCase;

class IndexPageTest extends TestCase
{
    public function test_index_page_loads_without_logging_in(): void
    {
        $result = $this->get(route('homepage'));
        $result->assertStatus(200);
    }

    public function test_index_page_loads_in_english(): void
    {
        session()->put('language', 'en');
        $result = $this->get(route('homepage'));
        $result->assertStatus(200);
    }

    public function test_index_page_loads_in_spanish(): void
    {
        session()->put('language', 'es');
        $result = $this->get(route('homepage'));
        $result->assertStatus(200);
    }

    public function test_index_page_loads_in_arabic(): void
    {
        session()->put('language', 'ar');
        $result = $this->get(route('homepage'));
        $result->assertStatus(200);
    }
}
