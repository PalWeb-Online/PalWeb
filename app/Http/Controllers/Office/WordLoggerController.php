<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\TermResource;
use App\Models\FeedbackComment;
use App\Models\Inflection;
use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WordLoggerController extends Controller
{
    public function index(): \Inertia\Response
    {
        $missingInflections = [];
        foreach (Inflection::whereIn('form', ['ap', 'pp', 'nv'])->get() as $inflection) {
            if (Term::where('translit', $inflection->translit)->doesntExist()) {
                $missingInflections[] = $inflection;
            }
        }
        $missingInflections = collect($missingInflections);

        return Inertia::render('Office/WordLogger/Index', [
            'section' => 'office',
            'feedbackComments' => FeedbackComment::all(),
            'fromSentences' => DB::table('sentence_term')->whereNull('term_id')->get(),
            'missingInflections' => $missingInflections,
        ]);
    }

    public function term(?Term $term = null): \Inertia\Response
    {
        $term?->load([
            'root',
            'pronunciations',
            'attributes',
            'spellings',
            'relatives',
            'patterns',
            'glosses.attributes',
            'inflections',
        ]);

        return Inertia::render('Office/WordLogger/Term', [
            'section' => 'office',
            'term' => $term
                ? new TermResource($term)->additional(['detail' => true])
                : null
        ]);
    }
}
