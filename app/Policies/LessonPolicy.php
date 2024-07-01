<?php

namespace App\Policies;

use App\Models\User;

/**
 * Checks if the user has permission to access various Lesson features. Example: Can this user access a lesson?
 */
class LessonPolicy
{
    /**
     * A list of actions we might take on a lesson
     */
    public static $actions = [
        'view' => 'view lessons',
        'create' => 'create lessons',
        'delete' => 'delete lessons',
        'update' => 'update lessons',
        'publish' => 'publish lessons',
        'unpublish' => 'unpublish lessons',
    ];

    /**
     * Returns true if provided user has permission to access lesson index
     */
    public function viewLessonIndex(User $auth): bool
    {
        return $this->viewLesson($auth);
    }

    /**
     * Returns true if provided user has permission to access a lesson
     */
    public function viewLesson(User $auth): bool
    {
        return $auth->hasPermissionTo(static::$actions['view']);
    }
}
