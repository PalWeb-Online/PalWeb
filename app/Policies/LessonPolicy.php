<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Lesson $lesson): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $lesson->published && $lesson->isUnlockedFor($user);
        }

        return false;
    }

    public function viewActivity(User $user, Lesson $lesson): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $lesson->published
                && $lesson->isUnlockedFor($user)
                && $lesson->getProgressFor($user)['stage'] > 1;
        }

        return false;
    }
}
