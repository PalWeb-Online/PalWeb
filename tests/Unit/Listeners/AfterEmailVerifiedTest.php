<?php

namespace Tests\Unit\Listeners;

use App\Mail\UserVerified;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AfterEmailVerifiedTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_an_email_with_link_to_join_discord()
    {
        Mail::fake();
        $user = User::factory()->create();
        event(new Verified($user));

        Mail::assertSent(UserVerified::class);
    }
}
