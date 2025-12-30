<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $progress = $this->getProgressFor($request->user());
        $stage = $progress['stage'] ?? 1;

        $document = $this->document;

        if ($document && isset($document['skills'])) {
            $document['skills'] = collect($document['skills'])->map(function ($skill) use ($stage) {
                if ($stage < 2) {
                    $skill['blocks'] = [];
                }
                return $skill;
            })->all();
        }

        return [
            'id' => $this->id,
            'group' => $this->group,
            'unit' => $this->whenLoaded('unit', fn() => [
                'id' => $this->unit->id,
                'title' => $this->unit->title,
                'position' => $this->unit->position
            ]),
            'slug' => $this->slug,
            'title' => $this->title,
            'document' => $document,
            'description' => $this->description,
            'deck' => $this->whenLoaded('deck', function() {
                return new DeckResource($this->deck);
            }),
            'activity' => $this->whenLoaded('activity', function() use ($stage) {
                return $stage > 1 ? new ActivityResource($this->activity) : null;
            }),
            'dialog' => $this->whenLoaded('dialog', function() use ($stage) {
                return $stage > 2 ? new DialogResource($this->dialog) : null;
            }),
            'unlock_conditions' => $this->unlock_conditions,
            'published' => (bool) $this->published,
            'progress' => $progress,
        ];
    }
}
