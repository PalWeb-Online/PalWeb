<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AudioResource extends JsonResource
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
            'filename' => $this->filename,
            'speaker' => new SpeakerResource($this->whenLoaded('speaker')),
            'pronunciation' => $this->whenLoaded('pronunciation', function () {
                return $this->pronunciation->load(['term', 'dialect']);
            }),
            'created_at' => $this->created_at->format('j F Y')
        ];
    }
}
