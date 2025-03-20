<?php

namespace App\Listeners;

use App\Mail\PasswordReset;
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
     */
    public function handle(\Illuminate\Auth\Events\PasswordReset $event): void
    {
        Mail::to($event->user)->send(new PasswordReset($event->user));
    }
}
