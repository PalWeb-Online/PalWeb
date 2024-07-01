<?php

namespace App\Policies;

use App\Models\User;

/**
 * Checks if the user has permission to access various Lesson features. Example: Can this user access a lesson?
 */
class ExplorePolicy
{
    /**
     * A list of actions we might take on a lesson
     */
    public static $actions = [
        'view' => 'view explore page',
        'create' => 'create explore page',
        'delete' => 'delete explore page',
        'update' => 'update explore page',
        'publish' => 'publish explore page',
        'unpublish' => 'unpublish explore page',
    ];

    /**
     * Returns true if provided user has permission to access explore index
     */
    public function viewIndex(User $auth): bool
    {
        return $this->viewExplore($auth);
    }

    /**
     * Returns true if provided user has permission to access explore page
     */
    public function viewExplore(User $auth): bool
    {
        return $auth->hasPermissionTo(static::$actions['view']);
    }
}
