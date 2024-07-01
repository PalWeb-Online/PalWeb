<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\TextPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TextPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_show()
    {
        $this->roles();
        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($this->getItem()->viewText($user));
    }

    public function test_admin_can_view_show()
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole();
        $this->assertTrue($this->getItem()->viewText($user));
    }

    public function test_normal_user_can_not_view_show()
    {
        $this->roles();
        $user = User::factory()->create();
        $this->assertFalse($this->getItem()->viewText($user));
    }

    public function test_student_can_view_index()
    {
        $this->roles();
        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($this->getItem()->viewIndex($user));
    }

    public function test_admin_can_view_index()
    {
        $this->roles();
        $user = User::factory()->create()->grantAdminRole();
        $this->assertTrue($this->getItem()->viewIndex($user));
    }

    public function test_normal_user_can_not_view_lesson_index()
    {
        $this->roles();
        $user = User::factory()->create();
        $this->assertFalse($this->getItem()->viewIndex($user));
    }

    protected function getItem()
    {
        return new TextPolicy();
    }
}
