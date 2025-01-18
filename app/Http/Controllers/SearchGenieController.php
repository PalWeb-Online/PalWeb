<?php

namespace App\Http\Controllers;

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
            'terms' => $results['terms']->take(10),
            'sentences' => $results['sentences']->take(10),
            'decks' => $results['decks']->take(10),
        ]);
    }

    public function getFilterOptions(): JsonResponse
    {
        $categories = Term::select('category')->distinct()->pluck('category');
        $attributes = Attribute::select('attribute')->distinct()->pluck('attribute');
        $forms = Pattern::select('form')->distinct()->pluck('form');
        $singularPatterns = Pattern::select('pattern')
            ->where('type', 'singular')
            ->distinct()
            ->pluck('pattern');
        $pluralPatterns = Pattern::select('pattern')
            ->where('type', 'plural')
            ->distinct()
            ->pluck('pattern');

        return response()->json([
            'categories' => $categories,
            'attributes' => $attributes,
            'forms' => $forms,
            'singularPatterns' => $singularPatterns,
            'pluralPatterns' => $pluralPatterns,
        ]);
    }
}
