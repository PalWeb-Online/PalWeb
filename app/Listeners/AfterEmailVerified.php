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
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $badge = Badge::where('name', 'I\'m Just Happy to Be Here')->first();

        $event->user->badges()->attach($badge);

        session()->flash('notification', ['type' => 'congrats', 'message' => __('badges.get', ['badge' => $badge->name])]);

        Mail::to($event->user)->send(new UserVerified($event->user));
    }
}
