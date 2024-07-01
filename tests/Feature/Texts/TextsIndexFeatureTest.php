<?php

namespace Tests\Feature\Texts;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TextsIndexFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_logged_in()
    {
        $result = $this->get(route('tx'));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('unauth');
    }

    public function test_admin_can_access()
    {
        $this->roles();

        $user = User::factory()->create()->grantAdminRole();
        Sanctum::actingAs($user);

        $result = $this->get(route('tx'));
        $result->assertStatus(200);
    }

    public function test_student_can_access()
    {
        $this->roles();

        $user = User::factory()->create()->grantStudentRole();
        Sanctum::actingAs($user);

        $result = $this->get(route('tx'));
        $result->assertStatus(200);
    }

    public function test_non_subscriber_can_not_access()
    {
        $this->roles();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Should be redirected to subscribe page
        $result = $this->get(route('tx'));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('dashboard.subscription');
    }
}
