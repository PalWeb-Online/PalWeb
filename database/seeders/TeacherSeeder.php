<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::doesntHave('teacher')
            ->where('id', '!=', 1)
            ->take(12)
            ->get()
            ->each(function (User $user) {
                if (! $user->hasRole('student')) {
                    $user->grantStudentRole();
                }

                Teacher::factory()->for($user)->create();
            });
    }
}
