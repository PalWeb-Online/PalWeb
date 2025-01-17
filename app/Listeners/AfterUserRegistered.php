<?php

namespace App\Listeners;

use App\Mail\UserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class AfterUserRegistered
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Send a welcome email to the new user
        Mail::to($event->user)->send(new UserRegistered($event->user));
    }
}
