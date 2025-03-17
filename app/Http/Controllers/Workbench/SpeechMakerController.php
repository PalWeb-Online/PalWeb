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
    public function dialog(?Dialog $dialog = null): \Inertia\Response
    {
        $dialog?->load(['sentences']);

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'mode' => 'dialog',
            'model' => 'dialog',
            'dialog' => $dialog ? new DialogResource($dialog) : null,
        ]);
    }

    public function dialogSentence(?Dialog $dialog): \Inertia\Response
    {
        $dialog->loadCount(['sentences']);

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'mode' => 'dialog',
            'model' => 'sentence',
            'dialog' => new DialogResource($dialog),
        ]);
    }

    public function sentence(?Sentence $sentence = null): \Inertia\Response
    {
        $sentence?->load(['dialog']);

        return Inertia::render('Workbench/SpeechMaker/SpeechMaker', [
            'section' => 'workbench',
            'mode' => 'sentence',
            'model' => 'sentence',
            'sentence' => $sentence ? new SentenceResource($sentence) : null,
        ]);
    }
}
