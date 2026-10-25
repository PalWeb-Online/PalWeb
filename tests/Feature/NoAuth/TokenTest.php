<?php

namespace Tests\Feature\NoAuth;

use Tests\TestCase;

class TokenTest extends TestCase
{
    public function test_token_endpoint_returns_only_csrf_token(): void
    {
        $this
            ->getJson('/api/token')
            ->assertOk()
            ->assertJsonStructure([
                'csrfToken',
            ])
            ->assertJsonMissingPath('locale')
            ->assertJsonMissingPath('utcOffsetMinutes');
    }
}
