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
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProfileChanged $event)
    {
        $badge = Badge::where('name', 'We\'re Happy to Have You')->first();

        if (! $event->user->badges->contains($badge->id)) {
            $event->user->badges()->attach($badge);
            $this->flasher->addInfo(__('badges.get', ['badge' => $badge->name]));
        }
    }
}
