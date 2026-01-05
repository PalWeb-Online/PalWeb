<?php

namespace App\Listeners;

use App\Mail\UserSubscribed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AfterSubscriptionCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->billable;

        if ($user->subscribed('default') || $user->onTrial()) {
            if (! $user->isStudent()) {
                $user->grantStudentRole();
                \App\Services\LessonService::syncUserProgress($user);

                try {
                    Mail::to($user)->send(new UserSubscribed($user));

                } catch (\Throwable $e) {
                    Log::error("Failed to send subscription email: ".$e->getMessage());
                }
            }
        }
    }
}
