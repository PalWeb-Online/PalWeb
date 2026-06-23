<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TermShowResource extends TermResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),

            'root' => $this->when($this->relationLoaded('root') && $this->root !== null,
                fn () => new RootResource($this->root, $this->resource)
            ),
            'usage' => $this->usage,
            'image' => $this->image,
            'etymology' => $this->etymology,
            'attributes' => $this->whenLoaded('attributes', fn () => $this->sorted_tags),
            'pronunciations_count' => $this->whenCounted('pronunciations'),
            'spellings' => $this->whenLoaded('spellings'),
            'relatives' => $this->whenLoaded('relatives', fn () => $this->relatives->map(fn ($relative) => [
                'id' => $relative->id,
                'slug' => $relative->slug,
                'term' => $relative->term,
                'translit' => $relative->translit,
                'type' => $relative->pivot->type,
                'gloss_id' => $relative->pivot->gloss_id,
            ])),
            'patterns' => $this->whenLoaded('patterns', fn () => $this->patterns->map(fn ($pattern) => [
                'type' => $pattern->type,
                'form' => $pattern->form,
                'pattern' => $pattern->pattern,
                'pattern_alias' => $pattern->pattern_alias,
            ])),
            'glosses' => $this->whenLoaded('glosses', fn () => $this->glosses->map(fn ($gloss) => [
                'id' => $gloss->id,
                'gloss' => $gloss->gloss,
                'attributes' => $gloss->attributes,
                'sentences' => SentenceResource::collection($this->gloss_sentences[$gloss->id]['sentences'] ?? []),
                'sentences_count' => $this->gloss_sentences[$gloss->id]['sentences_count'] ?? 0,
            ])),
            'decks' => DeckResource::collection($this->whenLoaded('decks')),
        ];
    }
}
