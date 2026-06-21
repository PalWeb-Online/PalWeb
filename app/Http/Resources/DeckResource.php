<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeckResource extends JsonResource
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
            'model_class' => 'deck',
            'name' => $this->name,
            'description' => $this->description,
            'private' => $this->private,
            'isPinned' => $this->is_pinned,
            'pinCount' => \Maize\Markable\Models\Bookmark::count($this->resource),
            'created_at' => $this->created_at->format('j F Y'),
            'author' => new UserResource($this->author),
            'terms' => $this->whenLoaded('terms', fn () => TermResource::collection($this->terms->sortBy('position')->values())),
            'terms_count' => $this->terms_count ?? 0,
            'scores' => ScoreResource::collection($this->whenLoaded('scores')),
            'stats' => $this->whenLoaded('scores', fn () => $this->score_stats),
            'lesson' => $this->when($this->lesson, function() use ($request) {
                return [
                    'id' => $this->lesson?->id,
                    'global_position' => $this->lesson?->global_position,
                    'progress' => $request->user()?->getLessonProgress()[$this->lesson?->id] ?? null,
                    'scores_count' => $request->user()?->getScoreCounts() ?? null,
                ];
            }),
            'unlocked' => $request->user()?->can('interact', $this->resource),
        ];
    }
}
