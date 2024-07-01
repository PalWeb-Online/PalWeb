<?php

namespace App\Listeners;

use App\Events\ModelPinned;
use App\Models\Badge;
use App\Models\Term;
use Flasher\Prime\FlasherInterface;

class AwardTermPinnedBadge
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
    public function handle(ModelPinned $event)
    {
        $badge = Badge::where('name', 'Baby\'s First Words')->first();

        if (!$event->user->badges->contains($badge->id)) {

            if (Term::whereHasBookmark($event->user)->count() >= 10) {
                $event->user->badges()->attach($badge);
                $this->flasher->addInfo(__('badges.get', ['badge' => $badge->name]));
            }
        }
    }
}
