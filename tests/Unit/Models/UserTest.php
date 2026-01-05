<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_returns_true_if_user_is_admin(): void
    {
        $this->roles();
        $this->assertTrue(User::factory()->create()->grantAdminRole()->isAdmin());
    }

    public function test_is_admin_returns_false_if_user_is_not_admin(): void
    {
        $this->roles();
        $this->assertFalse(User::factory()->create()->isAdmin());
    }

    public function test_get_roles_as_string_returns_admin_in_list_of_roles(): void
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole();
        $this->assertEquals('Admin', $user->getRolesAsString());
    }

    public function test_get_roles_as_string_returns_student_in_list_of_roles(): void
    {
        $this->roles();
        $user = User::factory()->create()->grantStudentRole();
        $this->assertEquals('Student', $user->getRolesAsString());
    }

    public function test_get_roles_as_string_returns_a_comma_separated_list_of_roles(): void
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole()->grantStudentRole();
        $this->assertEquals('Admin,Student', $user->getRolesAsString());
    }

    public function test_grant_admin_role_turns_user_into_an_administrator(): void
    {
        $this->roles();

        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $this->assertFalse($user->hasRole('admin'));

        $user->grantAdminRole();
        $user->refresh();

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_revoke_admin_role_removes_user_from_admin(): void
    {
        $this->roles();
        /** @var User $user */
        $user = User::factory()->create();

        $user->grantAdminRole();
        $this->assertTrue($user->hasRole('admin'));

        $user->revokeAdminRole();
        $user->refresh();

        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_grant_student_role_turns_user_into_a_student(): void
    {
        $this->roles();

        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $this->assertFalse($user->hasRole('student'));

        $user->grantStudentRole();
        $user->refresh();

        $this->assertTrue($user->hasRole('student'));
    }

    public function test_revoke_student_role_removes_user_from_students(): void
    {
        $this->roles();
        $user = User::factory()->create();

        $user->grantStudentRole();
        $this->assertTrue($user->hasRole('student'));

        $user->revokeStudentRole();
        $user->refresh();

        $this->assertFalse($user->hasRole('student'));
    }
}
