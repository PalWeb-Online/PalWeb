<?php

namespace App\Http\Resources;

use App\Models\Deck;
use App\Models\Sentence;
use App\Models\Term;
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
            'username' => $this->username,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'roles' => $this->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
            'decks' => DeckResource::collection($this->whenLoaded('decks')),
            'pinned' => [
//                todo: filter by private
                'decks' => DeckResource::collection(Deck::with(['author', 'terms'])->whereHasBookmark($request->user())->get()),
                'terms' => TermResource::collection(Term::whereHasBookmark($request->user())->get()),
                'sentences' => SentenceResource::collection(Sentence::whereHasBookmark($request->user())->get()),
            ],
        ];

    }
}
