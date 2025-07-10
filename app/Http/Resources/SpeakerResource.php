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
                'ar_name' => $this->user->ar_name,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar,
                'private' => $this->user->private
            ],
            'dialect' => $this->whenLoaded('dialect', function () {
                return [
                    'id' => $this->dialect->id,
                    'name' => $this->dialect->name,
                ];
            }),
            'location' => [
                'id' => $this->location->id,
                'name_ar' => $this->location->name_ar,
                'name_en' => $this->location->name_en,
            ],
            'fluency' => $this->fluency,
            'fluency_alias' => $this->fluency_alias,
            'gender' => $this->gender,
            'audios' => AudioResource::collection($this->whenLoaded('audios')),
            'audios_count' => $this->whenCounted('audios'),
        ];
    }
}
