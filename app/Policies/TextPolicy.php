<?php

namespace App\Policies;

use App\Models\User;

/**
 * Checks if the user has permission to access various Texts features. Example: Can this user access a text?
 */
class TextPolicy
{
    /**
     * A list of actions we might take on a lesson
     */
    public static $actions = [
        'view' => 'view texts',
        'create' => 'create texts',
        'delete' => 'delete texts',
        'update' => 'update texts',
        'publish' => 'publish texts',
        'unpublish' => 'unpublish texts',
    ];

    /**
     * Returns true if provided user has permission to access index page
     */
    public function viewIndex(User $auth): bool
    {
        return $this->viewText($auth);
    }

    /**
     * Returns true if provided user has permission to access a lesson
     */
    public function viewText(User $auth): bool
    {
        return $auth->hasPermissionTo(static::$actions['view']);
    }
}
