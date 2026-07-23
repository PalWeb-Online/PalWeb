<?php

namespace App\Listeners;

use App\Events\UserNotificationSent;
use App\Mail\UserVerified;
use App\Models\Badge;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AfterEmailVerified
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $badge = Badge::where('key', 'user_verified')->first();

        if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists()) {
            $event->user->badges()->attach($badge);

            UserNotificationSent::dispatch(
                $event->user->id,
                __('badges.get', ['badge' => $badge->title]),
            );
        }

        try {
            Mail::to($event->user)->send(new UserVerified($event->user));

        } catch (\Throwable $e) {
            Log::error("Failed to send subscription email: ".$e->getMessage());
        }
    }
}
