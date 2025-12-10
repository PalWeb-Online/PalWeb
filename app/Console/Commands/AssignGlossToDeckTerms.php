<?php

namespace App\Console\Commands;

use App\Models\Term;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignGlossToDeckTerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deck:assign-gloss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill missing gloss_id values in deck_term pivot table using the first Gloss of the related Term';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rows = DB::table('deck_term')
            ->whereNull('gloss_id')
            ->get();

        foreach ($rows as $row) {
            $term = Term::find($row->term_id);
            $gloss = $term->glosses()->first();

            DB::table('deck_term')
                ->where('deck_id', $row->deck_id)
                ->where('term_id', $row->term_id)
                ->update(['gloss_id' => $gloss->id]);

            $this->info("Updated deck_id {$row->deck_id}, term_id {$row->term_id} with gloss_id {$gloss->id}.");
        }

        $this->info('Done fixing gloss_id values.');
    }
}
