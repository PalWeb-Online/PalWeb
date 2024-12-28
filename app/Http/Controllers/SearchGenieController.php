<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchGenieController extends Controller
{
    public function search(Request $request, SearchService $searchService)
    {
        $searchTerm = $request->input('search');

        if (!$searchTerm) {
            return response()->json([
                'terms' => [],
                'sentences' => [],
                'decks' => [],
            ]);
        }

        $results = $searchService->search($searchTerm, true, true);

        return response()->json($results);
    }
}
