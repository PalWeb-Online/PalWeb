<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
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
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SubscriptionCancelled $event)
    {
        /** @var User $user */
        $user = $event->billable;
        $user->revokeStudentRole();
    }
}
