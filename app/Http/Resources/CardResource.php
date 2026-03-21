<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $level = $this->mastery_level;

        return [
            'id' => $this->id,
            'term' => $this->whenLoaded('term', function () {
                return new TermResource($this->term->loadMissing('pronunciations'))
                    ->additional(['detail' => true]);
            }),
            'repetitions' => $this->repetitions,
            'lapses' => $this->lapses,
            'step' => $this->step,
            'interval_days' => $this->interval_days,
            'ease_factor' => $this->ease_factor,
            'due_at' => $this->due_at?->format('Y-m-d'),
            'due_at_human' => $this->due_at?->format('j F Y'),
            'last_reviewed_at' => $this->last_reviewed_at?->format('Y-m-d'),
            'last_reviewed_at_human' => $this->last_reviewed_at?->format('j F Y'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'suspended_at' => $this->suspended_at?->format('Y-m-d'),
            'mastery_score' => $this->mastery_score,
            'mastery_rank' => $level->value,
            'mastery_label' => $level->label(),
            'mastery_message' => $level->message(),
            'next_intervals' => $this->next_intervals ?? [],
        ];
    }
}
