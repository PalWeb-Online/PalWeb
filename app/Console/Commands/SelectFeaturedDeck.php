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
    protected $description = 'Selects the Featured Deck.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $deck = Deck::where('private', false)->inRandomOrder()->first();
        Cache::put('featured-deck', $deck, now()->addWeek());

        return Command::SUCCESS;
    }
}
