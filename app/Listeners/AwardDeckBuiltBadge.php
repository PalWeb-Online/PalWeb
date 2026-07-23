<?php

namespace App\Listeners;

use App\Events\DeckBuilt;
use App\Events\UserNotificationSent;
use App\Models\Badge;

class AwardDeckBuiltBadge
{
    /**
     * Handle the event.
     */
    public function handle(DeckBuilt $event): void
    {
        $badge = Badge::where('key', 'created_deck')->first();

        if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists() && $event->user->decks->count() >= 1) {
            $event->user->badges()->attach($badge);

            UserNotificationSent::dispatch(
                $event->user->id,
                __('badges.get', ['badge' => $badge->title]),
            );
        }
    }
}
