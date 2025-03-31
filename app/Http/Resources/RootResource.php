<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RootResource extends JsonResource
{
    protected ?object $term;

    public function __construct($resource, $term = null)
    {
        parent::__construct($resource);
        $this->term = $term;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rootData = $this->generateRoot($this->term);

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
