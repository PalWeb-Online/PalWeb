<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DialogResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'media' => $this->media,
            'sentences' => $this->whenLoaded('sentences', function() {
                return $this->sentences->map(function ($sentence) {
                    return new SentenceResource($sentence)->additional(['terms' => false]);
                });
            }),
            'sentences_count' => $this->sentences_count,
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
