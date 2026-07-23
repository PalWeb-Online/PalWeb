<?php

namespace App\Listeners;

use App\Events\UserNotificationSent;
use App\Mail\UserSubscribed;
use App\Models\Badge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AfterSubscriptionCreated
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->billable;

        $badge = Badge::where('key', 'user_subscribed')->first();

        if ($user->subscribed('default') || $user->onTrial()) {
            if (! $user->isStudent()) {
                $user->grantStudentRole();
                \App\Services\LessonService::syncUserProgress($user);

                if ($badge && ! $event->user->badges()->whereKey($badge->id)->exists()) {
                    $user->badges()->attach($badge);

                    UserNotificationSent::dispatch(
                        $event->user->id,
                        __('badges.get', ['badge' => $badge->title]),
                    );
                }

                try {
                    Mail::to($user)->send(new UserSubscribed($user));

                } catch (\Throwable $e) {
                    Log::error("Failed to send subscription email: ".$e->getMessage());
                }
            }
        }
    }
}
