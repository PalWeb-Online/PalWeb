<?php

namespace App\Policies;

use App\Models\Dialog;
use App\Models\User;

class DialogPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Dialog $dialog): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            $lesson = $dialog->lesson;

            if (!$lesson) return true;

            if (!$lesson->published) return false;

            return $user->getLessonStage($lesson) >= 3;
        }

        return false;
    }
}
