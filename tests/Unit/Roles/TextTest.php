<?php

namespace Tests\Unit\Roles;

use App\Models\User;
use App\Policies\LessonPolicy;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests to ensure that text permissions are correct for each role. Example: can an admin view a text?
 */
class TextTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_perform_all_actions_on_a_text()
    {
        $this->roles();
        $user = User::factory()->create();
        $user->grantAdminRole();
        $user->refresh();

        foreach ($this->getActions() as $key => $val) {
            $this->assertTrue($user->hasPermissionTo($val), 'admin can '.$val);
        }
    }

    public function test_student_can_only_view_texts()
    {
        (new RoleSeeder)->run();
        $user = User::factory()->create();
        $user->grantStudentRole();
        $user->refresh();

        $actions = $this->getActions();
        $this->assertTrue($user->hasPermissionTo($actions['view']));

        // Everything else should fail to allow permission
        unset($actions['view']);
        foreach ($actions as $key => $val) {
            $this->assertFalse($user->hasPermissionTo($val), 'student cannot '.$val);
        }
    }

    protected function getActions()
    {
        return LessonPolicy::$actions;
    }
}
