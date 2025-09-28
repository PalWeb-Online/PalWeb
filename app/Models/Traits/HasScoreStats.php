<?php

namespace App\Models\Traits;

trait HasScoreStats
{
    public function getScoreStatsAttribute()
    {
        $scores = $this->scores;

        $latest = $scores->first();
        $highest = $scores->sortByDesc('score')->first();
        $average = $scores->avg('score');
        $count = $scores->count();

        $latestTrend = null;
        $highestTrend = null;
        $averageTrend = null;

        if ($latest) {
            $previous = $scores->skip(1)->first();

            if ($previous) {
                $latestTrend = $latest->score > $previous->score ? 'up' : 'down';
                if ($latest->score === $previous->score) {
                    $latestTrend = null;
                }
            } else {
                $latestTrend = 'up';
            }

            if ($scores->count() > 1) {
                $allExceptLatest = $scores->slice(1);

                $highestWithoutLatest = $allExceptLatest->max('score');

                if ($latest->score > $highestWithoutLatest) {
                    $highestTrend = 'up';
                }

                $averageWithoutLatest = $allExceptLatest->avg('score');

                $averageTrend = $latest->score > $averageWithoutLatest ? 'up' : 'down';
                if ($latest->score === $averageWithoutLatest) {
                    $averageTrend = null;
                }
            } else {
                $highestTrend = 'up';
                $averageTrend = 'up';
            }
        }

        return [
            'latest' => $latest?->score,
            'latest_date' => $latest?->created_at?->format('j F Y'),
            'latest_trend' => $latestTrend,
            'highest' => $highest?->score,
            'highest_date' => $highest?->created_at?->format('j F Y'),
            'highest_trend' => $highestTrend,
            'average' => round($average, 2),
            'average_trend' => $averageTrend,
            'count' => $count,
        ];
    }
}
