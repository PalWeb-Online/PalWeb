<?php

namespace App\Console\Commands;

use App\Models\Term;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SelectWordOfTheDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'select:wordoftheday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Selects a Term to feature.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $featuredTerm = Term::whereNotNull('image')->with(['pronunciations'])->inRandomOrder()->first();
        Cache::put('word-of-the-day', $featuredTerm, now()->addDay());

        return Command::SUCCESS;
    }
}
