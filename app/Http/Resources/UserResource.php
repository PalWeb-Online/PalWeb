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
            'ar_name' => $this->ar_name,
            'username' => $this->username,
            'avatar_url' => $this->avatar_url,
            'private' => $this->private,
            'decks_count' => $this->whenCounted('decks'),
            'audios_count' => $this->whenCounted('audios'),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')->values()),
        ];
    }
}
