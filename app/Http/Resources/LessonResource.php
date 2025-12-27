<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    private function withContent(): bool
    {
        return $this->additional['content'] ?? false;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $progress = $this->getProgressFor($request->user());
        $stage = $progress['stage'] ?? 1;

        $deck = $this->deck;
        $activity = $stage > 1 ? $this->activity : null;
        $dialog = $stage > 2 ? $this->dialog : null;

        if ($this->withContent()) {
            $deck?->load(['terms.pronunciations', 'scores']);
            $activity?->load(['scores']);
            $dialog?->load('sentences')
                ->setRelation('sentences',
                    $dialog->sentences->map(function ($sentence) {
                        return new SentenceResource($sentence)->additional(['terms' => false]);
                    })
                );
        }

        return [
            'id' => $this->id,
            'group' => $this->group,
            'unit' => new UnitResource($this->unit),
            'slug' => $this->slug,
            'title' => $this->title,
            'document' => $this->when($stage >= 2, $this->document),
            'description' => $this->description,
            'deck' => new DeckResource($deck),
            'activity' => new ActivityResource($activity),
            'dialog' => new DialogResource($dialog),
            'unlock_conditions' => $this->unlock_conditions,
            'published' => $this->published,
            'progress' => $this->getProgressFor($request->user()),
        ];
    }
}
