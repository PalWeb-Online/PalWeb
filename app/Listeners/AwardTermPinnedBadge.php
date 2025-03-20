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
    ) {}

    /**
     * Handle the event.
     */
    public function handle(ModelPinned $event): void
    {
        $badge = Badge::where('name', 'Baby\'s First Words')->first();

        if (! $event->user->badges->contains($badge->id)) {

            if (Term::whereHasBookmark($event->user)->count() >= 10) {
                $event->user->badges()->attach($badge);
                session()->flash('notification', ['type' => 'congrats', 'message' => __('badges.get', ['badge' => $badge->name])]);
            }
        }
    }
}
