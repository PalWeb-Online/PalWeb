<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Models\Dialog;
use App\Models\Gloss;
use App\Models\Sentence;
use Inertia\Inertia;

class SpeechMakerController extends Controller
{
    public function index(): \Inertia\Response
    {
        $termsMissingSentences = [];

        Gloss::inRandomOrder()->chunk(250, function ($glosses) use (&$termsMissingSentences) {
            foreach ($glosses as $gloss) {
                if (count($gloss->term->sentences($gloss->id)->get()) < 1) {
                    $gloss->term->gloss = $gloss->gloss;
                    $termsMissingSentences[] = $gloss->term;
                }

                if (count($termsMissingSentences) === 25) {
                    return false;
                }
            }
        });

        return Inertia::render('Office/SpeechMaker/SpeechMaker', [
            'section' => 'office',
            'step' => 'select',
            'mode' => 'dialog',
            'allDialogs' => DialogResource::collection(Dialog::orderByDesc('id')->take(10)->get()),
            'termsMissingSentences' => TermResource::collection($termsMissingSentences),
        ]);
    }

    public function dialog(?Dialog $dialog = null): \Inertia\Response
    {
        $dialog?->load(['sentences'])
            ->setRelation(
                'sentences',
                $dialog->sentences->map(function ($sentence) {
                    return new SentenceResource($sentence)->additional(['terms' => false]);
                })
            );

        return Inertia::render('Office/SpeechMaker/SpeechMaker', [
            'section' => 'office',
            'step' => 'build',
            'mode' => 'dialog',
            'dialog' => $dialog ? new DialogResource($dialog) : null,
        ]);
    }

    public function dialogSentence(Dialog $dialog): \Inertia\Response
    {
        $dialog->loadCount(['sentences']);

        return Inertia::render('Office/SpeechMaker/SpeechMaker', [
            'section' => 'office',
            'step' => 'build',
            'mode' => 'sentence',
            'dialog' => new DialogResource($dialog),
        ]);
    }

    public function sentence(?Sentence $sentence = null): \Inertia\Response
    {
        $sentence?->load(['dialog']);

        return Inertia::render('Office/SpeechMaker/SpeechMaker', [
            'section' => 'office',
            'step' => 'build',
            'mode' => 'sentence',
            'sentence' => $sentence ? new SentenceResource($sentence) : null,
        ]);
    }
}
