<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\TermResource;
use App\Models\Attribute;
use App\Models\Dialect;
use App\Models\Inflection;
use App\Models\Term;
use App\Services\TermRelativeService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class WordLoggerController extends Controller
{
    public function __construct(
        protected TermService $termService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Office/WordLogger/Index', [
            'section' => 'office',
        ]);
    }

    public function relatives(): JsonResponse
    {
        $unlinkedReciprocalTerms = $this->termResources(
            $this->termsWithRelativeRows(
                DB::table('term_relative')
                    ->whereNull('reciprocal_id')
                    ->select('term_id')
                    ->distinct()
            )
        );

        $missingGlossTerms = $this->termResources(
            $this->termsWithRelativeRows(
                DB::table('term_relative')
                    ->whereIn('type', TermRelativeService::GLOSS_TYPES)
                    ->whereNull('gloss_id')
                    ->select('term_id')
                    ->distinct()
            )
        );

        $missingTypeTerms = $this->termResources(
            $this->termsWithRelativeRows(
                DB::table('term_relative')
                    ->whereNull('type')
                    ->select('term_id')
                    ->distinct()
            )
        );

        return response()->json([
            'unlinkedReciprocalTerms' => $unlinkedReciprocalTerms,
            'missingGlossTerms' => $missingGlossTerms,
            'missingTypeTerms' => $missingTypeTerms,
        ]);
    }

    public function sentences(): JsonResponse
    {
        return response()->json([
            'fromSentences' => DB::table('sentence_term')
                ->whereNull('term_id')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function inflections(): JsonResponse
    {
        $missingInflections = collect();

        foreach (Inflection::whereIn('form', ['ap', 'pp', 'nv'])->get() as $inflection) {
            if (Term::where('translit', $inflection->translit)->doesntExist()) {
                $missingInflections->push($inflection);
            }
        }

        return response()->json([
            'missingInflections' => $missingInflections,
        ]);
    }

    public function term(?Term $term = null): Response
    {
        return Inertia::render('Office/WordLogger/Term', [
            'section' => 'office',
            'termId' => $term?->id,
            'editorData' => [
                'attributes' => Attribute::query()
                    ->select(['id', 'model', 'attribute', 'category'])
                    ->orderBy('id')
                    ->get(),
                'dialects' => Dialect::query()
                    ->select(['id', 'name'])
                    ->orderBy('id')
                    ->get(),
            ],
        ]);
    }

    private function termsWithRelativeRows(\Illuminate\Database\Query\Builder $query): \Illuminate\Support\Collection
    {
        return Term::query()
            ->whereIn('id', $query)
            ->with(['glosses', 'pronunciations.audios'])
            ->orderBy('term')
            ->get();
    }

    private function termResources(\Illuminate\Support\Collection $terms): array
    {
        $this->termService->hydratePronunciations($terms);

        return TermResource::collection($terms)->resolve();
    }
}
