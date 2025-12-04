<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'position' => $this->position,
            'title' => $this->title,
            'lessons' => $this->lessons->map(fn ($lesson) => [
                'id' => $lesson->id,
                'position' => $lesson->position,
                'slug' => $lesson->slug,
                'title' => $lesson->title,
                'unlocked' => $lesson->isUnlockedFor($request->user()),
                'completed' => $lesson->getProgressFor($request->user())['completed'],
                'published' => $lesson->published,
            ]),
            'published' => $this->published
        ];
    }
}
