<?php

namespace App\Policies;

use App\Models\Audio;
use App\Models\User;

class AudioPolicy
{
    public function update(User $user, Audio $audio): bool
    {
        return $this->modify($user, $audio);
    }

    public function delete(User $user, Audio $audio): bool
    {
        return $this->modify($user, $audio);
    }

    private function modify(User $user, Audio $audio): bool
    {
        return $user->id === $audio->speaker->user_id;
    }
}
