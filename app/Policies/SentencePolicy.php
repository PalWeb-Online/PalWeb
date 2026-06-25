<?php

namespace App\Policies;

use App\Models\Sentence;
use App\Models\User;

class SentencePolicy
{
    public function delete(User $user, Sentence $sentence): bool
    {
        return $user->isAdmin();
    }
}
