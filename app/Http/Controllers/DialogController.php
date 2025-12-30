<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDialogRequest;
use App\Http\Requests\UpdateDialogRequest;
use App\Http\Resources\DialogResource;
use App\Http\Resources\SentenceResource;
use App\Models\Dialog;
use App\Models\Sentence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class DialogController extends Controller
{
    public function index(): \Inertia\Response|RedirectResponse
    {
        return Inertia::render('Academy/Dialogs/Index', [
            'section' => 'academy',
            'dialogs' => DialogResource::collection(Dialog::paginate(25)->onEachSide(1)),
        ]);
    }

    public function show(Dialog $dialog): \Inertia\Response|RedirectResponse
    {
        Gate::authorize('view', $dialog);

        return Inertia::render('Academy/Dialogs/Show', [
            'section' => 'academy',
            'dialog' => new DialogResource(
                $dialog->load('sentences')
                    ->setRelation('sentences',
                        $dialog->sentences->map(function ($sentence) {
                            return new SentenceResource($sentence)->additional(['terms' => false]);
                        })
                    )
            ),
        ]);

        //        View::share('pageDescription',
        //            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');
    }

    public function store(StoreDialogRequest $request): RedirectResponse
    {
        $dialog = Dialog::create($request->all());
        $this->linkSentences($dialog, $request->sentences);

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $dialog->title])]);

        return to_route('speech-maker.dialog', $dialog);
    }

    public function update(UpdateDialogRequest $request, Dialog $dialog): RedirectResponse
    {
        $dialog->update($request->all());
        $this->linkSentences($dialog, $request->sentences);

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $dialog->title])]);

        return to_route('speech-maker.dialog', $dialog);
    }

    private function linkSentences(Dialog $dialog, array $sentences): void
    {
        foreach ($sentences as $sentenceData) {
            $sentence = Sentence::find($sentenceData['id']);

            $sentence?->update([
                'dialog_id' => $dialog->id,
                'speaker' => $sentenceData['speaker'],
                'position' => $sentenceData['position'],
            ]);
        }

        foreach ($dialog->sentences as $sentence) {
            if (! in_array($sentence->id, array_column($sentences, 'id'))) {
                $sentence->update([
                    'dialog_id' => null,
                ]);
            }
        }
    }

    public function destroy(Dialog $dialog): RedirectResponse
    {
        $dialog->delete();
        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $dialog->title])]);

        return to_route('dialogs.index');
    }
}
