<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;

class TeacherPolicy
{
    public function viewAny(User $actor): bool
    {
        return true;
    }

    public function view(User $actor, Teacher $teacher): bool
    {
        return true;
    }

    public function create(User $actor, User $target): bool
    {
        if ($target->teacher()->exists()) {
            return false;
        }

        return $target->isStudent() && $actor->isAdmin();
//        return $target->isStudent() && ($actor->isAdmin() || $actor->is($target));
    }

    public function update(User $actor, Teacher $teacher): bool
    {
        return $actor->isAdmin() || $actor->id === $teacher->user_id;
    }

    public function delete(User $actor, Teacher $teacher): bool
    {
        return $actor->isAdmin() || $actor->id === $teacher->user_id;
    }
}
