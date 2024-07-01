<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\Deck;
use App\Models\Sentence;
use App\Models\Term;
use App\Models\User;
use Illuminate\Console\Command;

class AwardBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badges:award';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retroactively awards badges to users who meet their conditions.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $verificationBadge = Badge::where('name', 'I\'m Just Happy to Be Here')->first();
        $termBadge = Badge::where('name', 'Baby\'s First Words')->first();
        $sentenceBadge = Badge::where('name', 'Loquacious')->first();
        $savedDecksBadge = Badge::where('name', 'Mine!')->first();
        $createdDecksBadge = Badge::where('name', 'Word Collector')->first();

        $users = User::all();
        
        foreach ($users as $user) {
            if (!$user->badges->contains($verificationBadge->id) && $user->email_verified_at) {
                $user->badges()->attach($verificationBadge);
                $this->info("Awarded $verificationBadge->name Badge to $user->username");
            }

            if (!$user->badges->contains($termBadge->id) && Term::whereHasBookmark($user)->count() >= 10) {
                $user->badges()->attach($termBadge);
                $this->info("Awarded $termBadge->name Badge to $user->username");
            }

            if (!$user->badges->contains($sentenceBadge->id) && Sentence::whereHasBookmark($user)->count() >= 5) {
                $user->badges()->attach($sentenceBadge);
                $this->info("Awarded $sentenceBadge->name Badge to $user->username");
            }

            if (!$user->badges->contains($savedDecksBadge->id) && Deck::whereHasBookmark($user)->count() >= 5) {
                $user->badges()->attach($savedDecksBadge);
                $this->info("Awarded $savedDecksBadge->name Badge to $user->username");
            }

            if (!$user->badges->contains($createdDecksBadge->id) && count($user->decks) >= 1) {
                $user->badges()->attach($createdDecksBadge);
                $this->info("Awarded $createdDecksBadge->name Badge to $user->username");
            }
        }

        return Command::SUCCESS;
    }
}
