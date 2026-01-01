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
            'isPinned' => $this->isPinned(),
            'pinCount' => \Maize\Markable\Models\Bookmark::count($this->resource),
            'created_at' => $this->created_at->format('j F Y'),
            'author' => $this->whenLoaded('author', [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'username' => $this->author->username,
                'avatar' => $this->author->avatar,
                'private' => $this->author->private,
            ]),
            'terms' => $this->whenLoaded('terms', function () {
                return TermResource::collection($this->terms->sortBy('position')->values());
            }),
            'terms_count' => $this->terms_count ?? 0,
            'scores' => ScoreResource::collection($this->whenLoaded('scores')),
            'stats' => $this->whenLoaded('scores', function () {
                return $this->score_stats;
            }),
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
