<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function update(User $user, Card $card): bool
    {
        return $this->modify($user, $card);
    }

    public function delete(User $user, Card $card): bool
    {
        return $this->modify($user, $card);
    }

    private function modify(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }
}
