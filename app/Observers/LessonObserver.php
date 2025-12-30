<?php

namespace App\Observers;

use App\Listeners\SyncLessonProgress;
use App\Models\Lesson;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class LessonObserver  implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Lesson "created" event.
     */
    public function created(Lesson $lesson): void
    {
        if (! $lesson->published) {
            return;
        }

        dispatch(new SyncLessonProgress($lesson));
    }

    /**
     * Handle the Lesson "updated" event.
     */
    public function updated(Lesson $lesson): void
    {
        if (($lesson->wasChanged('published') || $lesson->wasChanged('unlock_conditions')) && $lesson->published) {
            dispatch(new SyncLessonProgress($lesson));
        }
    }

    /**
     * Handle the Lesson "deleted" event.
     */
    public function deleted(Lesson $lesson): void
    {
        //
    }

    /**
     * Handle the Lesson "restored" event.
     */
    public function restored(Lesson $lesson): void
    {
        //
    }

    /**
     * Handle the Lesson "force deleted" event.
     */
    public function forceDeleted(Lesson $lesson): void
    {
        //
    }
}
