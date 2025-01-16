<?php

namespace App\Listeners;

use App\Events\ModelPinned;
use App\Models\Badge;
use App\Models\Deck;
use Flasher\Prime\FlasherInterface;

class AwardDeckPinnedBadge
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
    public function handle(ModelPinned $event)
    {
        $badge = Badge::where('name', 'Mine!')->first();

        if (! $event->user->badges->contains($badge->id)) {

            if (Deck::whereHasBookmark($event->user)->count() >= 5) {
                $event->user->badges()->attach($badge);
                $this->flasher->addInfo(__('badges.get', ['badge' => $badge->name]));
            }
        }
    }
}
