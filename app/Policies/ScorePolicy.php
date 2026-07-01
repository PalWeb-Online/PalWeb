<?php

namespace App\Policies;

use App\Models\Score;
use App\Models\User;

class ScorePolicy
{
    public function delete(User $user, Score $score): bool
    {
        return $user->isAdmin() || $user->id === $score->user_id;
    }
}
