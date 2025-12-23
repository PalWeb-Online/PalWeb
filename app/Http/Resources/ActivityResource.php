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
            'lesson' => [
                'id' => $this->lesson->id,
                'slug' => $this->lesson->slug,
            ],
            'title' => $this->title,
            'document' => $this->document,
            'scores' => ScoreResource::collection($this->whenLoaded('scores')),
            'stats' => $this->whenLoaded('scores', function () {
                return $this->score_stats;
            }),
            'published' => $this->published,
        ];
    }
}
