<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model_class' => 'activity',
            'title' => $this->title,
            'document' => $this->document,
            'scores' => ScoreResource::collection($this->whenLoaded('scores')),
            'stats' => $this->whenLoaded('scores', function () {
                return $this->score_stats;
            }),
            'published' => $this->published,
            'lesson' => $this->when($this->lesson, function () use ($request) {
                return [
                    'id' => $this->lesson->id,
                    'global_position' => $this->lesson->global_position,
                    'progress' => $request->user()?->getLessonProgress()[$this->lesson?->id] ?? null,
                    'scores_count' => $request->user()?->getScoreCounts() ?? null,
                ];
            }),
            'unlocked' => $request->user()?->can('view', $this->resource),
        ];
    }
}
