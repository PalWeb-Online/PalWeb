<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'ar_name' => $this->ar_name,
            'avatar' => $this->avatar,
            'home' => $this->home,
            'bio' => $this->bio,
            'private' => $this->private,
            'dialect' => $this->whenLoaded('dialect'),
            'badges' => $this->whenLoaded('badges'),
            'created_at' => $this->created_at->format('j F Y'),
            'created_ago' => $this->created_at->diffForHumans(),
            'decks_count' => $this->decks->count(),
            'audios_count' => $this->speaker?->audios->count() ?? 0,
        ];
    }
}
