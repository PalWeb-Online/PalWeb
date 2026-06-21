<?php

namespace App\Http\Resources;

use App\Services\SentenceService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SentenceResource extends JsonResource
{
    private function withTerms(): bool
    {
        return $this->additional['terms'] ?? true;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sentence' => $this->sentence,
            'translit' => $this->translit,
            'trans' => $this->trans,
            'dialog' => $this->whenLoaded('dialog', function () use ($request) {
                if ($request->user()?->can('view', $this->dialog)) {
                    return [
                        'id' => $this->dialog?->id,
                        'title' => $this->dialog?->title,
                    ];

                } else {
                    return null;
                }
            }),
            'speaker' => $this->speaker,
            'position' => $this->position,
            'audio' => $this->getAudio(),
            'isPinned' => $this->isPinned(),
            'terms' => $this->withTerms() ? $this->getTerms() : [],
            'terms' => $this->withTerms()
                ? app(SentenceService::class)->getSentenceTerms($this->resource)->toArray()
                : [],
            'isPinned' => $this->is_pinned,
        ];
    }
}
