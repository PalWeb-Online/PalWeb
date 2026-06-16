<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserShowResource extends UserResource
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

            'home' => $this->home,
            'bio' => $this->bio,
            'created_at' => $this->created_at->format('j F Y'),
            'created_ago' => $this->created_at->diffForHumans(),
            'dialect' => $this->whenLoaded('dialect'),
            'badges' => $this->whenLoaded('badges'),
        ];
    }
}
