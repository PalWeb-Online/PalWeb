<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SentenceResource extends JsonResource
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
            'sentence' => $this->sentence,
            'translit' => $this->translit,
            'trans' => $this->trans,
            'dialog' => $this->whenLoaded('dialog', [
                'id' => $this->dialog?->id,
                'title' => $this->dialog?->title,
            ]),
            'speaker' => $this->speaker,
            'position' => $this->position,
            'audio' => $this->getAudio(),
            'isPinned' => $this->isPinned(),
            'terms' => !$request->routeIs('sentences.index') ? $this->getAudio() : [],
        ];
    }
}
