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
//        todo: translit will always be the default translit; it does not reflect the user's dialect
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'term' => $this->term,
            'category' => $this->category,
            'translit' => $this->translit,
            'audio' => $this->getAudio(),
            'isPinned' => $this->isPinned(),
            'glosses' => $this->glosses->map(function ($gloss) {
                return [
                    'id' => $gloss->id,
                    'gloss' => $gloss->gloss,
                ];
            }),
            'deckPivot' => $this->whenPivotLoaded('deck_term', function () {
                return [
                    'gloss_id' => $this->pivot->gloss_id,
                    'position' => $this->pivot->position,
                ];
            }),
            'sentencePivot' => $this->whenPivotLoaded('sentence_term', function () {
                return [
                    'gloss_id' => $this->pivot->gloss_id,
                    'sent_term' => $this->pivot->sent_term,
                    'sent_translit' => $this->pivot->sent_translit,
                    'position' => $this->pivot->position,
                ];
            }),
        ];
    }
}
