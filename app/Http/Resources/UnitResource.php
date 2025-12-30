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
        $user = $request->user();
        $userProgress = $user ? $user->getLessonProgress() : [];

        return [
            'id' => $this->id,
            'position' => $this->position,
            'title' => $this->title,
            'lessons' => $this->lessons->map(function ($lesson) use ($user, $userProgress) {
                $progress = $userProgress[$lesson->id] ?? null;

                return [
                    'id' => $lesson->id,
                    'position' => $lesson->position,
                    'slug' => $lesson->slug,
                    'title' => $lesson->title,
                    'unlocked' => $user?->isAdmin() || isset($progress),
                    'completed' => (bool) ($progress['completed'] ?? false),
                    'published' => (bool) $lesson->published,
                ];
            }),
            'published' => $this->published
        ];
    }
}
