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
        $deck = $this->deck;
        $dialog = $this->dialog;

        if ($this->withContent()) {
            $deck?->load(['terms.pronunciations', 'scores']);
            $dialog?->load('sentences')
                ->setRelation('sentences',
                    $dialog->sentences->map(function ($sentence) {
                        return new SentenceResource($sentence)->additional(['terms' => false]);
                    })
                );
        }

        return [
            'id' => $this->id,
            'unit' => new UnitResource($this->unit),
            'slug' => $this->slug,
            'title' => $this->title,
            'skills' => $this->skills,
            'deck' => new DeckResource($deck),
            'dialog' => new DialogResource($dialog),
            'progress' => $this->getProgressFor($request->user()),
            'published' => $this->published,
        ];
    }
}
