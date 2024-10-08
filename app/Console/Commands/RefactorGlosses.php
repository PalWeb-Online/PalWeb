<?php

namespace App\Console\Commands;

use App\Models\Gloss;
use App\Models\Attribute;
use Illuminate\Console\Command;

class RefactorGlosses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:glosses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactors glosses.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $glosses = Gloss::whereNotNull('attribute')->get();

        foreach ($glosses as $gloss) {

            if ($gloss->structure) {
                $attribute = Attribute::firstWhere('attribute', $gloss->structure);
            } else {
                $attribute = Attribute::firstWhere('attribute', $gloss->attribute);
            }

            $gloss->attributes()->attach($attribute);
        }

        return Command::SUCCESS;
    }
}
