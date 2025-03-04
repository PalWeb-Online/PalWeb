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
            $priority = [
                'collective' => 1,
                'demonym' => 1,
                'defect' => 1,
                'pseudo' => 1,
                'masculine' => 2,
                'feminine' => 2,
                'plural' => 2,
                'idiom' => 3,
                'clitic' => 3,
            ];

            $sortedAttributes = $this->attributes->pluck('attribute')->toArray();
            usort($sortedAttributes, function ($a, $b) use ($priority) {
                return ($priority[$a] ?? PHP_INT_MAX) - ($priority[$b] ?? PHP_INT_MAX);
            });

            $detail = [
                'attributes' => $sortedAttributes,
                'spellings' => $this->spellings,
                'variants' => $this->variants,
                'references' => $this->references,
                'components' => $this->components,
                'descendants' => $this->descendants,
                'etymology' => $this->etymology,
                'patterns' => $this->patterns->map(function ($pattern) {
                    return [
                        'type' => $pattern->type,
                        'form' => $pattern->form,
                        'pattern' => $pattern->pattern_alias,
                    ];
                }),
                'glosses' => $this->glosses->map(function ($gloss) {
                    return [
                        'id' => $gloss->id,
                        'gloss' => $gloss->gloss,
                        'attributes' => $gloss->attributes->map(function ($attribute) {
                            return [
                                'attribute' => $attribute->attribute,
                                'category' => $attribute->category,
                            ];
                        }),
                        'synonyms' => $gloss->synonyms->map(function ($relative) {
                            return [
                                'slug' => $relative->slug,
                                'term' => $relative->term,
                                'translit' => $relative->translit,
                            ];
                        }),
                        'antonyms' => $gloss->antonyms->map(function ($relative) {
                            return [
                                'slug' => $relative->slug,
                                'term' => $relative->term,
                                'translit' => $relative->translit,
                            ];
                        }),
                        'valences' => $gloss->valences->map(function ($relative) {
                            return [
                                'slug' => $relative->slug,
                                'term' => $relative->term,
                                'translit' => $relative->translit,
                                'type' => $relative->pivot->type,
                            ];
                        }),
                        'sentences' => SentenceResource::collection($this->sentences[$gloss->id] ?? []),
                    ];
                }),
                'inflections' => [
                    'host' => $this->inflections->whereIn('form', ['genitive', 'accusative']),
                    'response' => $this->inflections->where('form', 'resp'),
                    'construct' => $this->inflections->where('form', 'cnst'),
                    'other' => $this->inflections->whereNotIn('form', ['cnst', 'resp', 'genitive', 'accusative']),
                ],
                'decks' => DeckResource::collection($this->whenLoaded('decks')),
            ];
        }

        return array_merge($term, $detail);
    }
}
