<?php

namespace Tests\Feature\Texts;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TextsShowFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_logged_in(): void
    {
        $text = $this->createText();
        $result = $this->get(route('tx.tx', ['text' => $text->text]));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('denied');
    }

    public function test_admin_can_access(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantAdminRole();
        Sanctum::actingAs($user);

        $item = $this->createText();
        $result = $this->get(route('tx.tx', ['text' => $item->text]));
        $result->assertStatus(200);
    }

    public function test_student_can_access(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantStudentRole();
        Sanctum::actingAs($user);

        $item = $this->createText();
        $result = $this->get(route('tx.tx', ['text' => $item->text]));
        $result->assertStatus(200);
    }

    public function test_non_subscriber_can_not_access(): void
    {
        $this->roles();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Should be redirected to subscribe page
        $item = $this->createText();
        $result = $this->get(route('tx.tx', ['text' => $item->text]));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('dashboard.subscription');
    }

    protected function createText($name = '01')
    {
        /**
         * For now we have hard coded the text but by making this function now, when we move text to the database
         * we will only need to do this once instead of 1000 times
         */
        return (object) [
            'name' => $name,
            'text' => strtolower($name),
            'data' => [],
        ];
    }
}
