<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RootResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rootData = $this->generateRoot();

        return [
            'id' => $this->id,
            'root' => $this->root,
            'ar' => $rootData['ar'],
            'en' => $rootData['en'],
            'all' => $rootData['all'],
            'terms' => TermResource::collection($this->whenLoaded('terms')),
        ];
    }
}
