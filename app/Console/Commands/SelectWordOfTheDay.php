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
    protected $description = 'Selects the Word of the Day.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $wordOfTheDay = Term::whereNotNull('image')->with(['pronunciations'])->inRandomOrder()->first();
        Cache::put('word-of-the-day', $wordOfTheDay, now()->addDay());

        return Command::SUCCESS;
    }
}
