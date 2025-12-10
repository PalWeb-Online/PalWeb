<?php

namespace App\Listeners;

use App\Mail\UserSubscribed;
use Illuminate\Support\Facades\Mail;
use Spark\Events\SubscriptionCreated;

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
    public function handle(SubscriptionCreated $event): void
    {
        $user = $event->billable;
        $user->grantStudentRole();

        Mail::to($user)->send(new UserSubscribed($user));
    }
}
