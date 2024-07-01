<?php

namespace App\Listeners;

use App\Events\DeckBuilt;
use App\Models\Badge;
use Flasher\Prime\FlasherInterface;

class AwardDeckBuiltBadge
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
    public function handle(DeckBuilt $event)
    {
        $badge = Badge::where('name', 'Word Collector')->first();

        if (!$event->user->badges->contains($badge->id)) {

            if ($event->user->decks->count() >= 1) {
                $event->user->badges()->attach($badge);
                $this->flasher->addInfo(__('badges.get', ['badge' => $badge->name]));
            }
        }
    }
}
