<?php

namespace App\Policies;

use App\Models\Avatar;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AvatarPolicy
{
    private const int MAX_AVATARS_PER_USER = 8;

    public function viewAny(User $actor, User $target): bool
    {
        return $actor->isAdmin() || $actor->id === $target->id;
    }

    public function create(User $actor, User $target): Response
    {
        if (! $this->canSelectCustomAvatar($actor, $target)) {
            return Response::deny('Only Student subscribers may select custom avatars.');
        }

        if (! $this->canCreateCustomAvatar($actor, $target)) {
            return Response::deny('This user has reached the avatar upload limit.');
        }

        return Response::allow();
    }

    public function select(User $actor, User $target): Response
    {
        if (! $this->canSelectCustomAvatar($actor, $target)) {
            return Response::deny('Only Student subscribers may select custom avatars.');
        }

        return Response::allow();
    }

    public function delete(User $actor, Avatar $avatar): bool
    {
        return $actor->isAdmin() || $actor->id === $avatar->user_id;
    }

    private function canCreateCustomAvatar(User $actor, User $target): bool
    {
        return $target->uploadedAvatars()->count() >= self::MAX_AVATARS_PER_USER;
    }

    private function canSelectCustomAvatar(User $actor, User $target): bool
    {
        return $actor->isAdmin() || $target->isStudent();
    }
}
