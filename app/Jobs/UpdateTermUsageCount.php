<?php

namespace App\Jobs;

use App\Models\Term;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UpdateTermUsageCount implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array|Collection $termIds,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $termIds = collect($this->termIds)
            ->filter(fn ($termId) => ! is_null($termId))
            ->map(fn ($termId) => (int) $termId)
            ->filter(fn ($termId) => $termId > 0)
            ->unique()
            ->values();

        if ($termIds->isEmpty()) {
            return;
        }

        $counts = DB::table('sentence_term')
            ->whereNotNull('term_id')
            ->whereIn('term_id', $termIds)
            ->groupBy('term_id')
            ->selectRaw('term_id, COUNT(*) as usage_count')
            ->pluck('usage_count', 'term_id');

        foreach ($termIds as $termId) {
            DB::table('terms')
                ->where('id', $termId)
                ->update([
                    'usage_count' => (int) ($counts[$termId] ?? 0),
                ]);
        }
    }
}
