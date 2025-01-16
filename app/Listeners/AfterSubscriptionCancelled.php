<?php

namespace App\Listeners;

use App\Models\User;
use Spark\Events\SubscriptionCancelled;

class AfterSubscriptionCancelled
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
    public function handle(SubscriptionCancelled $event): void
    {
        /** @var User $user */
        $user = $event->billable;
        $user->revokeStudentRole();
    }
}
