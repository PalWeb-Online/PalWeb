<?php

namespace Tests\Feature\Lessons;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LessonsShowFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_logged_in(): void
    {
        $lesson = $this->createLesson();
        $result = $this->get(route('ls.ls', ['lesson' => $lesson->lesson]));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('unauth');
    }

    public function test_admin_can_access_lessons(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantAdminRole();
        Sanctum::actingAs($user);

        $lesson = $this->createLesson();
        $result = $this->get(route('ls.ls', ['lesson' => $lesson->lesson]));
        $result->assertStatus(200);
    }

    public function test_student_can_access_lessons(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantStudentRole();
        Sanctum::actingAs($user);

        $lesson = $this->createLesson();
        $result = $this->get(route('ls.ls', ['lesson' => $lesson->lesson]));
        $result->assertStatus(200);
    }

    public function test_non_subscriber_can_not_access_lessons(): void
    {
        $this->roles();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Should be redirected to subscribe page
        $lesson = $this->createLesson();
        $result = $this->get(route('ls.ls', ['lesson' => $lesson->lesson]));
        $result->assertStatus(302);
        $result->assertRedirectToRoute('dashboard.subscription');
    }

    protected function createLesson($name = 'u1')
    {
        /**
         * For now we have hard coded the lessons but by making this function now, when we move lessons to the database
         * we will only need to do this once instead of 1000 times
         */
        return (object) [
            'name' => $name,
            'lesson' => strtolower($name),
            'data' => [],
        ];
    }
}
