<?php

namespace App\Listeners;

use App\Mail\PasswordChanged;
use Illuminate\Support\Facades\Mail;

class AfterPasswordReset
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
    public function handle($event): void
    {
        // Send a password changed email to the user
        Mail::to($event->user)->send(new PasswordChanged($event->user));
    }
}
