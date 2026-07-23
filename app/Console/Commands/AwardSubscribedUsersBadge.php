<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Console\Command;

class AwardSubscribedUsersBadge extends Command
{
    protected $signature = 'badges:award-subscribed-users {--dry-run : Show how many users would be awarded without changing anything}';

    protected $description = 'Retroactively award the subscribed user badge to all student users.';

    public function handle(): int
    {
        $badge = Badge::where('key', 'user_subscribed')->first();

        if (! $badge) {
            $this->error('Could not find badge with key [user_subscribed].');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');

        $awarded = 0;

        User::student()
            ->whereDoesntHave('badges', function ($query) use ($badge) {
                $query->where('badges.id', $badge->id);
            })
            ->chunkById(100, function ($users) use ($badge, $dryRun, &$awarded) {
                foreach ($users as $user) {
                    $awarded++;

                    if (! $dryRun) {
                        $user->badges()->syncWithoutDetaching([$badge->id]);
                    }
                }
            });

        if ($dryRun) {
            $this->info("{$awarded} subscribed users would receive the [{$badge->title}] badge.");

            return self::SUCCESS;
        }

        $this->info("Awarded the [{$badge->title}] badge to {$awarded} subscribed users.");

        return self::SUCCESS;
    }
}
