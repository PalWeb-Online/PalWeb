<?php

namespace App\Listeners;

use App\Events\ModelPinned;
use App\Models\Badge;
use App\Models\Sentence;
use Flasher\Prime\FlasherInterface;

class AwardSentencePinnedBadge
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
        $badge = Badge::where('name', 'Loquacious')->first();

        if (! $event->user->badges->contains($badge->id)) {

            if (Sentence::whereHasBookmark($event->user)->count() >= 5) {
                $event->user->badges()->attach($badge);
                session()->flash('notification', ['type' => 'congrats', 'message' => __('badges.get', ['badge' => $badge->name])]);
            }
        }
    }
}
