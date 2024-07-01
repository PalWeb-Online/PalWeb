<?php

namespace Tests\Unit\Roles;

use App\Models\User;
use App\Policies\LessonPolicy;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests to ensure that lesson permissions are correct for each role. Example: can an admin view a lesson?
 */
class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_perform_all_actions_on_a_lesson()
    {
        (new RoleSeeder)->run();
        $user = User::factory()->create();
        $user->grantAdminRole();
        $user->refresh();

        foreach (LessonPolicy::$actions as $key => $val) {
            $this->assertTrue($user->hasPermissionTo($val), 'admin can '.$val);
        }
    }

    public function test_student_can_only_view_lessons()
    {
        (new RoleSeeder)->run();
        $user = User::factory()->create();
        $user->grantStudentRole();
        $user->refresh();

        $actions = LessonPolicy::$actions;
        $this->assertTrue($user->hasPermissionTo($actions['view']));

        // Everything else should fail to allow permission
        unset($actions['view']);
        foreach ($actions as $key => $val) {
            $this->assertFalse($user->hasPermissionTo($val), 'student cannot '.$val);
        }
    }
}
