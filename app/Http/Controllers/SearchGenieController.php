<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Pattern;
use App\Models\Term;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchGenieController extends Controller
{
    public function search(Request $request, SearchService $searchService)
    {
        $searchTerm = $request->input('search', '') ?? '';
        $filters = $request->only(['category', 'attribute', 'form', 'singular', 'plural']);

        $results = $searchService->search($searchTerm, $filters, true, true);

        return response()->json($results);
    }

    public function getFilterOptions()
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
