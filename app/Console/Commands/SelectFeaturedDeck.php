<?php

namespace App\Console\Commands;

use App\Models\Deck;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SelectFeaturedDeck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'select:featureddeck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Selects a Deck to feature.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $deck = Deck::inRandomOrder()->with(['terms'])->first();
        Cache::put('featured-deck', $deck, now()->addWeek());

        return Command::SUCCESS;
    }
}
