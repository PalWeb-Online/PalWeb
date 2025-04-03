<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchGenieController extends Controller
{
    public function getResults(Request $request, SearchService $searchService): JsonResponse
    {
        $filters = $request->only(['search', 'match', 'pinned', 'category', 'attribute', 'form', 'singular', 'plural']);

        $results = [
            'terms' => collect(),
            'decks' => collect(),
            'sentences' => collect(),
        ];

        if (collect($filters)->except(['match'])->some(fn ($value) => ! empty($value))) {
            $results = $searchService->search($filters, true, true);
        }

        return response()->json([
            'terms' => TermResource::collection($results['terms']->take(10)),
            'decks' => DeckResource::collection($results['decks']->take(10)),
            'sentences' => SentenceResource::collection(
                $results['sentences']->take(10)->map(function ($sentence) {
                    return new SentenceResource($sentence)->additional(['terms' => false]);
                })
            ),
        ]);
    }
}
