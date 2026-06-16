<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEditorResource extends UserResource
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

            'avatar' => $this->avatar,
            'avatar_id' => $this->avatar_id,
            'uploaded_avatars' => AvatarResource::collection($this->whenLoaded('uploadedAvatars')),
        ];
    }
}
