<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Models\Attribute;
use App\Models\Pattern;
use App\Models\Term;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchGenieController extends Controller
{
    public function getResults(Request $request, SearchService $searchService): JsonResponse
    {
        $filters = array_merge([
            'search' => '',
            'category' => '',
            'attribute' => '',
            'form' => '',
            'singular' => '',
            'plural' => '',
        ], $request->only(['search', 'category', 'attribute', 'form', 'singular', 'plural']));
        $filters = array_map(fn($value) => $value ?? '', $filters);

        $results = $searchService->search($filters, true, true);

        return response()->json([
            'terms' => TermResource::collection($results['terms']->take(10)),
            'sentences' => SentenceResource::collection($results['sentences']->take(10)),
            'decks' => DeckResource::collection($results['decks']->load(['author', 'terms'])->take(10)),
        ]);
    }
}
