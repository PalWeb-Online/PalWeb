<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTermUsageCount;
use App\Models\Term;
use Illuminate\Console\Command;

class CalculateTermUsageCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terms:calculate-usage-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate usage_count for all terms.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Term::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(500, function ($terms) {
                UpdateTermUsageCount::dispatch(
                    $terms->pluck('id')->all()
                );
            });

        $this->info('Dispatched usage count rebuild jobs.');
    }
}
