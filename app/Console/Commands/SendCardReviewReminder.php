<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\CardReviewReminder;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendCardReviewReminder extends Command
{
    protected $signature = 'cards:send-review-reminder';
    protected $description = 'Send web push reminder to review due Cards';

    public function handle(): int
    {
        User::query()
            ->whereHas('pushSubscriptions')
            ->whereHas('cards', function (Builder $query) {
                $query->due();
            })
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    $dueCount = $user->cards()
                        ->due()
                        ->count();

                    $user->notify(new CardReviewReminder($dueCount));
                }
            });

        $this->info('Sent reminders to review due Cards.');

        return self::SUCCESS;
    }
}
