<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssignSentenceTermUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentences:assign-term-uuids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign UUIDs to existing sentence_term rows.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        DB::table('sentence_term')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('sentence_term')
                        ->where('id', $row->id)
                        ->update([
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
            });

        $this->info('UUIDs assigned to sentence_term rows.');

        return self::SUCCESS;
    }
}
