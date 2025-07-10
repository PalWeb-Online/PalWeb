<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Models\Dialog;
use App\Models\Sentence;
use Inertia\Inertia;

class SpeechMakerController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'step' => 'select',
            'collection' => DialogResource::collection(Dialog::orderByDesc('id')->take(10)->get()),
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

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'step' => 'build',
            'mode' => 'dialog',
            'dialog' => $dialog ? new DialogResource($dialog) : null,
        ]);
    }

    public function dialogSentence(?Dialog $dialog): \Inertia\Response
    {
        $dialog->loadCount(['sentences']);

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'step' => 'build',
            'mode' => 'sentence',
            'dialog' => new DialogResource($dialog),
        ]);
    }

    public function sentence(?Sentence $sentence = null): \Inertia\Response
    {
        $sentence?->load(['dialog']);

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'step' => 'build',
            'mode' => 'sentence',
            'sentence' => $sentence ? new SentenceResource($sentence) : null,
        ]);
    }
}
