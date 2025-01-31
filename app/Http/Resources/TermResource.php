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
        $pronunciationData = $this->getUserPronunciationData();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'term' => $this->term,
            'category' => $this->category,
            'translit' => $pronunciationData['translit'],
            'audio' => $pronunciationData['audio'],
            'isPinned' => $this->isPinned(),
            'glosses' => $this->glosses->map(function ($gloss) {
                return [
                    'id' => $gloss->id,
                    'gloss' => $gloss->gloss,
                ];
            }),
            'inflections' => $this->inflections->map(function ($inflection) {
                return [
                    'inflection' => $inflection->inflection,
                    'translit' => $inflection->translit,
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
