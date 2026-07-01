<?php

namespace App\Policies;

use App\Models\Term;
use App\Models\User;

class TermPolicy
{
    public function update(User $user, Term $term): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Term $term): bool
    {
        return $user->isAdmin();
    }
}
