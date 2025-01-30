<?php

namespace App\Http\Resources;

use App\Models\Term;
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
        $allTerms = $this->resource->allTerms();

        $terms = $allTerms->map(function ($sentenceTerm) {
            if ($sentenceTerm->id) {
                $term = Term::find($sentenceTerm->id);

                $termResource = new TermResource($term)->toArray(request());
                $termResource['sentencePivot'] = [
                    'gloss_id' => $sentenceTerm->gloss_id,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ];

                return $termResource;
            }

            return [
                'sentencePivot' => [
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ],
            ];
        });

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
            'terms' => $terms->values()->toArray(),
        ];
    }
}
