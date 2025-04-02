<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
{
    private function withDetail(): bool
    {
        return $this->additional['detail'] ?? false;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pronunciationData = $this->getUserPronunciationData();

        $term = [
            'id' => $this->id,
            'slug' => $this->slug,
            'term' => $this->term,
            'category' => $this->category,
            'audio' => $pronunciationData['audio'],
            'translit' => $pronunciationData['translit'],
            'pronunciations' => PronunciationResource::collection($pronunciationData['pronunciations']),
            'pronunciations_count' => $this->whenCounted('pronunciations'),
            'image' => $this->image,
            'glosses' => $this->glosses->map(function ($gloss) {
                return [
                    'id' => $gloss->id,
                    'gloss' => $gloss->gloss,
                    'attributes' => [],
                    'synonyms' => [],
                    'antonyms' => [],
                    'valences' => [],
                    'sentences' => [],
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
            'isPinned' => $this->isPinned(),
        ];

        $detail = [];

        if ($this->withDetail()) {
            $detail = [
                'root' => $this->when($this->relationLoaded('root') && $this->root !== null,
                    function () {
                        return new RootResource($this->root, $this->resource);
                    }
                ),
                'etymology' => $this->etymology,
                'attributes' => $this->sorted_attributes,
                'spellings' => $this->spellings,
                'relatives' => $this->relatives->map(function ($relative) {
                    return [
                        'id' => $relative->id,
                        'slug' => $relative->slug,
                        'term' => $relative->term,
                        'translit' => $relative->translit,
                        'type' => $relative->pivot->type,
                    ];
                }),
                'patterns' => $this->patterns->map(function ($pattern) {
                    return [
                        'type' => $pattern->type,
                        'form' => $pattern->form,
                        'pattern' => $pattern->pattern,
                        'pattern_alias' => $pattern->pattern_alias,
                    ];
                }),
                'glosses' => $this->glosses->map(function ($gloss) {
                    return [
                        'id' => $gloss->id,
                        'gloss' => $gloss->gloss,
                        'attributes' => $gloss->attributes,
                        'relatives' => $gloss->relatives->map(function ($relative) {
                            return [
                                'id' => $relative->id,
                                'slug' => $relative->slug,
                                'term' => $relative->term,
                                'translit' => $relative->translit,
                                'type' => $relative->pivot->type,
                            ];
                        }),
                        'sentences' => SentenceResource::collection($this->gloss_sentences[$gloss->id] ?? []),
                    ];
                }),
                'inflections' => $this->inflections,
                'usage' => $this->usage,
                'decks' => DeckResource::collection($this->whenLoaded('decks')),
            ];
        }

        return array_merge($term, $detail);
    }
}
