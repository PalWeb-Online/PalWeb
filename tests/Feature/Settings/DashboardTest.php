<?php

namespace Tests\Feature\Settings;

use App\Events\PasswordChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_logged_in_to_access_subscribe()
    {
        $result = $this->get(route('dashboard.subscription'));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('unauth');
    }

    public function test_loads_subscribe_page_if_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->get(route('dashboard.subscription'));
        $result->assertStatus(200);
    }

    public function test_cant_load_dashboard_if_not_logged_in()
    {
        $result = $this->get(route('dashboard'));
        $result->assertStatus(302);
    }

    public function test_can_load_dashboard_if_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->get(route('dashboard'));
        $result->assertStatus(200);
    }

    public function test_cant_load_settings_if_not_logged_in()
    {
        $user = User::factory()->create();

        $result = $this->get(route('settings.index'));
        $result->assertStatus(302);
    }

    public function test_loads_settings_if_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->get(route('settings.index'));
        $result->assertStatus(200);
    }

    public function test_settings_page_can_change_name()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->patch(route('settings.index', [
            'name' => 'potato man',
        ]));

        $result->assertStatus(200);
        $this->assertEquals(User::find($user->id)->name, 'potato man');
    }

    public function test_loads_password_page_if_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->get(route('settings.password'));

        $result->assertStatus(200);
    }

    public function test_fails_to_loads_password_page_if_not_logged_in()
    {
        $user = User::factory()->create();
        $result = $this->get(route('settings.password'));

        $result->assertStatus(302);
    }

    public function test_password_page_can_change_password()
    {
        Event::fake();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->patch(route('settings.password.update'), [
            'old' => 'password',
            'new' => 'password234',
            'confirm' => 'password234',
        ]);

        $user->refresh();

        $result->assertStatus(200);
        $this->assertTrue(Hash::check('password234', $user->password));

        Event::assertDispatched(PasswordChanged::class);
    }
}
