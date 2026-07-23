<?php

namespace App\Listeners;

use App\Events\ModelPinned;
use App\Events\UserNotificationSent;
use App\Models\Badge;
use App\Models\Term;

class AwardTermPinnedBadge
{
    /**
     * Handle the event.
     */
    public function handle(ModelPinned $event): void
    {
        $badge = Badge::where('key', 'pinned_terms')->first();

        if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists() && Term::whereHasBookmark($event->user)->count() >= 10) {
            $event->user->badges()->attach($badge);

            UserNotificationSent::dispatch(
                $event->user->id,
                __('badges.get', ['badge' => $badge->title]),
            );
        }
    }
}
