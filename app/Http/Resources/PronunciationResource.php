<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PronunciationResource extends JsonResource
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
            'translit' => $this->translit,
            'phonemic' => $this->phonemic,
            'phonetic' => $this->phonetic,
            'borrowed' => $this->borrowed,
            'dialect_id' => $this->dialect_id,
            'dialect' => $this->whenLoaded('dialect', function () {
                return [
                    'id' => $this->dialect->id,
                    'name' => $this->dialect->name,
                ];
            }),
            'audios' => AudioResource::collection($this->whenLoaded('audios')),
            'audios_count' => $this->audios_count,
            'term' => $this->whenLoaded('term', function () {
                return [
                    'slug' => $this->term->slug,
                    'term' => $this->term->term,
                ];
            }),
        ];
    }
}
