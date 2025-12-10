<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefactorBookmarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refactor-bookmarks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactor markable_type values in markable_bookmarks table to use simple model names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('markable_bookmarks')
            ->where('markable_type', \App\Models\Term::class)
            ->update(['markable_type' => 'term']);

        DB::table('markable_bookmarks')
            ->where('markable_type', \App\Models\Sentence::class)
            ->update(['markable_type' => 'sentence']);

        DB::table('markable_bookmarks')
            ->where('markable_type', \App\Models\Deck::class)
            ->update(['markable_type' => 'deck']);

        $this->info('Successfully updated markable_type values');
    }
}
