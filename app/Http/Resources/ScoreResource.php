<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreResource extends JsonResource
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
            'user_id' => $this->user_id,
            'scorable_id' => $this->scorable_id,
            'scorable_type' => class_basename($this->scorable_type),
            'settings' => $this->settings,
            'score' => $this->score,
            'results' => $this->results,
            'created_at' => $this->created_at->format('j F Y'),
        ];
    }
}
