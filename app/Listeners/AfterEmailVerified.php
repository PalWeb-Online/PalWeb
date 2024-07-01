<?php

namespace App\Listeners;

use App\Mail\UserVerified;
use App\Models\Badge;
use Flasher\Prime\FlasherInterface;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;

class AfterEmailVerified
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected FlasherInterface $flasher
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $badge = Badge::where('name', 'I\'m Just Happy to Be Here')->first();

        $event->user->badges()->attach($badge);
        $this->flasher->addInfo("You got the $badge->name Badge! Good job!");

        // Send an email confirming the succesful verification.
        Mail::to($event->user)->send(new UserVerified($event->user));
    }
}
