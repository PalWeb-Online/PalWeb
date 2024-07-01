<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\LessonPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_lesson()
    {
        $this->roles();
        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($this->getItem()->viewLesson($user));
    }

    public function test_admin_can_view_lesson()
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole();
        $this->assertTrue($this->getItem()->viewLesson($user));
    }

    public function test_normal_user_can_not_view_lesson()
    {
        $this->roles();
        $user = User::factory()->create();
        $this->assertFalse($this->getItem()->viewLesson($user));
    }

    public function test_student_can_view_lesson_index()
    {
        $this->roles();
        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($this->getItem()->viewLessonIndex($user));
    }

    public function test_admin_can_view_lesson_index()
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole();
        $this->assertTrue($this->getItem()->viewLessonIndex($user));
    }

    public function test_normal_user_can_not_view_lesson_index()
    {
        $this->roles();
        $user = User::factory()->create();
        $this->assertFalse($this->getItem()->viewLessonIndex($user));
    }

    protected function getItem()
    {
        return new LessonPolicy;
    }
}
