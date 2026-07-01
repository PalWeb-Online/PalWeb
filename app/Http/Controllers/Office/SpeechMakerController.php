<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Models\Dialog;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Models\Term;
use App\Services\TermService;
use Inertia\Inertia;

class SpeechMakerController extends Controller
{
    public function __construct(
        protected TermService $termService
    ) {
    }

    public function index(): \Inertia\Response
    {
        $termsMissingSentences = collect();

        Term::query()
            ->withItemData()
            ->with('glosses')
            ->inRandomOrder()
            ->chunk(250, function ($terms) use ($termsMissingSentences) {
                foreach ($terms as $term) {
                    foreach ($term->glosses as $gloss) {
                        if (! $term->sentences($gloss->id)->exists()) {
                            $termsMissingSentences->push($term);
                            if ($termsMissingSentences->count() >= 25) {
                                return false;
                            }
                            break;
                        }
                    }
                }
            });

        $this->termService->hydratePronunciations($termsMissingSentences);

        return Inertia::render('Office/SpeechMaker/Index', [
            'section' => 'office',
            'dialogs' => DialogResource::collection(Dialog::orderByDesc('id')->take(10)->get()),
            'terms' => TermResource::collection($termsMissingSentences),
        ]);
    }

    public function dialog(?Dialog $dialog = null): \Inertia\Response
    {
        return Inertia::render('Office/SpeechMaker/Dialog', [
            'section' => 'office',
            'dialogId' => $dialog?->id,
        ]);
    }

    public function dialogSentence(Dialog $dialog): \Inertia\Response
    {
        return Inertia::render('Office/SpeechMaker/Sentence', [
            'section' => 'office',
            'initialDialog' => [
                'id' => $dialog->id,
                'title' => $dialog->title,
            ]
        ]);
    }

    public function sentence(?Sentence $sentence = null): \Inertia\Response
    {
        return Inertia::render('Office/SpeechMaker/Sentence', [
            'section' => 'office',
            'sentenceId' => $sentence?->id,
        ]);
    }
}
