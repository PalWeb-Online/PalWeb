<?php

namespace App\Listeners;

use App\Events\ModelPinned;
use App\Events\UserNotificationSent;
use App\Models\Badge;
use App\Models\Deck;

class AwardDeckPinnedBadge
{
    /**
     * Handle the event.
     */
    public function handle(ModelPinned $event): void
    {
        $badge = Badge::where('key', 'pinned_decks')->first();

        if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists() && Deck::whereHasBookmark($event->user)->count() >= 5) {
            $event->user->badges()->attach($badge);

            UserNotificationSent::dispatch($event->user->id, [
                'key' => 'badge.notifications.awarded',
                'params' => [
                    'badge' => $badge->title,
                ]
            ]);
        }
    }
}
