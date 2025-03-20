<?php

namespace App\Listeners;

use App\Events\ProfileChanged;
use App\Models\Badge;
use Flasher\Prime\FlasherInterface;

class AwardProfileChangedBadge
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
    public function handle(ProfileChanged $event): void
    {
        $badge = Badge::where('name', 'We\'re Happy to Have You')->first();

        if (! $event->user->badges->contains($badge->id)) {
            $event->user->badges()->attach($badge);
            session()->flash('notification', ['type' => 'congrats', 'message' => __('badges.get', ['badge' => $badge->name])]);
        }
    }
}
