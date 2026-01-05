<?php

namespace Tests\Feature;

use App\Events\UserDeniedAccess;
use App\Events\UserViewed;
use App\Exceptions\UnauthorizedAccessException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_viewed_event_will_be_fired_off_on_every_page_view(): void
    {
        Config::set('app.track_page_views', true);
        Event::fake();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->get(route('homepage'));
        Event::assertDispatched(UserViewed::class);
        Config::set('app.track_page_views', false);
    }

    public function test_a_user_denied_access_event_will_be_fired_off_if_user_is_denied_access_to_api_route(): void
    {
        Config::set('app.track_page_views', true);
        $this->expectException(UnauthorizedAccessException::class);
        Event::fake();
        (new Controller)->failIfFalse(false);
        Event::assertDispatched(UserDeniedAccess::class);
        Config::set('app.track_page_views', false);
    }
}
