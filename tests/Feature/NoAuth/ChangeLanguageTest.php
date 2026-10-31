<?php

namespace Tests\Feature\NoAuth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ChangeLanguageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_change_language_sets_language_cookie(): void
    {
        foreach (['en', 'es', 'ar'] as $locale) {
            $response = $this->postJson("/api/language/{$locale}");

            $response
                ->assertOk()
                ->assertJson([
                    'success' => true,
                    'locale' => $locale,
                ])
                ->assertCookie('language', $locale);
        }
    }

    public function test_change_language_ignores_unsupported_locales(): void
    {
        foreach (['1', '4', 'potato', 'hello world', 'dog'] as $locale) {
            $response = $this->postJson("/api/language/{$locale}");

            $response
                ->assertOk()
                ->assertJson([
                    'success' => true,
                    'locale' => $locale,
                ])
                ->assertCookieMissing('language');
        }
    }
}
