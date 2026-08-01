<?php

namespace App\Listeners;

use App\Events\ProfileChanged;
use App\Events\UserNotificationSent;
use App\Models\Badge;

class AwardProfileChangedBadge
{
    /**
     * Handle the event.
     */
    public function handle(ProfileChanged $event): void
    {
        $badge = Badge::where('key', 'user_profile_updated')->first();

        if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists()) {
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
