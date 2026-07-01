<?php

namespace App\Policies;

use App\Models\FeedbackComment;
use App\Models\User;

class FeedbackCommentPolicy
{
    public function delete(User $user, FeedbackComment $feedbackComment): bool
    {
        return $user->isAdmin();
    }
}
