<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class ActivityPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Activity $activity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            $lesson = $activity->lesson;

            if ($lesson) {
                return $lesson->published
                    && $lesson->isUnlockedFor($user)
                    && $lesson->getProgressFor($user)['stage'] > 1;
            }

            return true;
        }

        return false;
    }
}
