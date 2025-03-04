<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpeakerResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar,
                'private' => $this->user->private
            ],
            'dialect' => $this->whenLoaded('dialect', function () {
                return [
                    'name' => $this->dialect->name,
                ];
            }),
            'location' => [
                'name_ar' => $this->location->name_ar,
                'name_en' => $this->location->name_en,
            ],
            'fluency' => $this->fluency_alias,
            'gender' => $this->gender,
            'audios_count' => $this->whenCounted('audios'),
        ];
    }
}
