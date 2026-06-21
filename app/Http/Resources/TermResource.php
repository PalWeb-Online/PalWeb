<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
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
            'slug' => $this->slug,
            'term' => $this->term,
            'category' => $this->category,
            'audio' => $this->selected_audio,
            'translit' => $this->selected_translit ?? $this->translit,
            'pronunciations' => PronunciationResource::collection($this->whenLoaded('pronunciations')),
            'inflections' => $this->whenLoaded('inflections'),
            'glosses' => $this->whenLoaded('glosses', fn () => $this->glosses->map(fn ($gloss) => [
                'id' => $gloss->id,
                'gloss' => $gloss->gloss,
            ])),
            'deckPivot' => $this->whenPivotLoaded('deck_term', fn () => [
                'gloss_id' => $this->pivot->gloss_id,
                'position' => $this->pivot->position,
            ]),
            'sentencePivot' => $this->whenPivotLoaded('sentence_term', fn () => [
                'gloss_id' => $this->pivot->gloss_id,
                'sent_term' => $this->pivot->sent_term,
                'sent_translit' => $this->pivot->sent_translit,
                'position' => $this->pivot->position,
            ]),
            'card' => $this->whenLoaded('cards',
                fn () => $this->cards->isNotEmpty() ? new CardResource($this->cards->first()) : null
            ),
            'isPinned' => $this->is_pinned,
        ];
    }
}
