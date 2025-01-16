<?php

namespace Tests\Feature\NoAuth;

use Tests\TestCase;

class ChangeLanguageTest extends TestCase
{
    public function test_change_language_sets_language_in_the_session(): void
    {
        // Should not have a language set to start
        $this->assertFalse(session()->has('language'));

        // Only some strings are accepted
        foreach (['en', 'es', 'ar'] as $val) {
            // language should be set after posting to the change language route
            $result = $this->post(route('language.change', $val));
            $this->assertEquals($val, session()->get('language'));

            // Should redirect back to prev page
            $result->assertStatus(302);
        }

        // set the session so we know what it is
        session()->put('language', 'en');

        // we dont accept these things
        foreach (['1', 4, 'potato', 'hello world', 'dog'] as $val) {
            // language should NOT be set after posting to the change language route
            $result = $this->post(route('language.change', $val));
            $this->assertNotEquals($val, session()->get('language'));

            // Should redirect back to prev page
            $result->assertStatus(302);
        }
    }
}
