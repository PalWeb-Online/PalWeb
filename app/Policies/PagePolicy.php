<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Page $page): bool
    {
        return $page->status === 'published' || $user?->isAdmin() === true;
    }
}
