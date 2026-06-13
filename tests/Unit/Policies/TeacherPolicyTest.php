<?php

namespace Tests\Unit\Policies;

use App\Models\Dialect;
use App\Models\Teacher;
use App\Models\User;
use App\Policies\TeacherPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Dialect::create(['name' => 'Test Dialect']);
        $this->roles();
    }

    public function test_student_without_teacher_profile_can_create_teacher_profile(): void
    {
        $user = User::factory()->create()->grantStudentRole();

        $this->assertTrue((new TeacherPolicy)->create($user));
    }

    public function test_user_without_student_role_cannot_create_teacher_profile(): void
    {
        $user = User::factory()->create();

        $this->assertFalse((new TeacherPolicy)->create($user));
    }

    public function test_user_with_existing_teacher_profile_cannot_create_another_teacher_profile(): void
    {
        $user = User::factory()->create()->grantStudentRole();
        Teacher::factory()->for($user)->create();

        $this->assertFalse((new TeacherPolicy)->create($user));
    }

    public function test_owner_can_update_and_delete_teacher_profile(): void
    {
        $user = User::factory()->create()->grantStudentRole();
        $teacher = Teacher::factory()->for($user)->create();
        $policy = new TeacherPolicy;

        $this->assertTrue($policy->update($user, $teacher));
        $this->assertTrue($policy->delete($user, $teacher));
    }

    public function test_admin_can_update_and_delete_any_teacher_profile(): void
    {
        $admin = User::factory()->create()->grantAdminRole();
        $teacher = Teacher::factory()->for(User::factory()->create())->create();
        $policy = new TeacherPolicy;

        $this->assertTrue($policy->update($admin, $teacher));
        $this->assertTrue($policy->delete($admin, $teacher));
    }
}
