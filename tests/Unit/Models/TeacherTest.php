<?php

namespace Tests\Unit\Models;

use App\Models\Dialect;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_belongs_to_user(): void
    {
        Dialect::create(['name' => 'Test Dialect']);
        $user = User::factory()->create();
        $teacher = Teacher::factory()->for($user)->create();

        $this->assertTrue($teacher->user->is($user));
    }

    public function test_user_has_one_teacher(): void
    {
        Dialect::create(['name' => 'Test Dialect']);
        $user = User::factory()->create();
        $teacher = Teacher::factory()->for($user)->create();

        $this->assertTrue($user->teacher->is($teacher));
    }
}
