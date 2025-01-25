<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDialogRequest;
use App\Http\Requests\UpdateDialogRequest;
use App\Models\Dialog;
use App\Models\Sentence;
use App\Models\Term;
use App\Policies\TextPolicy;
use App\Traits\RedirectsToSubscribe;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DialogController extends Controller
{
    use RedirectsToSubscribe;

//    todo: rename TextPolicy to DialogPolicy
    public function __construct(
        protected TextPolicy $can,
        protected FlasherInterface $flasher
    ) {
    }

    public function index(Request $request): \Illuminate\View\View|RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Dialog Library');
        View::share('pageDescription',
            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewIndex($auth));

            return view('dialogs.index', [
                'dialogs' => Dialog::paginate(15)->onEachSide(1),
            ]);
        });
    }

    public function show(Request $request, Dialog $dialog): \Illuminate\View\View|RedirectResponse
    {
        $auth = $request->user();

        $dialog->load('sentences.terms');

        View::share('pageTitle', 'Dialog: '.$dialog->title);
        View::share('pageDescription',
            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');

        return $this->redirectToSubscribeOnFail(function () use ($auth, $dialog) {
            $this->failIfFalse($this->can->viewText($auth));

            return view('dialogs.show', [
                'dialog' => $dialog,
                'bodyBackground' => 'yellow-pastel',
            ]);
        });
    }

    public function create(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Dialogger: Create Dialog');

        return view('dialogs.builder', [
            'layout' => 'app',
            'modelType' => 'dialog',
        ]);
    }

    public function edit($dialogId): \Illuminate\View\View
    {
        $dialog = Dialog::findOrFail($dialogId);

        $sentences = [];

        foreach ($dialog->sentences as $sentence) {
            $terms = [];

            foreach ($sentence->allTerms() as $sentenceTerm) {
                $term = Term::find($sentenceTerm->id);

                if ($term) {
                    $terms[] = [
                        'id' => $term->id,
                        'term' => $term->term,
                        'category' => $term->category,
                        'translit' => $term->translit,
                        'glosses' => $term->glosses->map(function ($gloss) {
                            return [
                                'id' => $gloss->id,
                                'gloss' => $gloss->gloss,
                            ];
                        })->toArray(),
                        'pivot' => [
                            'gloss_id' => $sentenceTerm->gloss_id,
                            'sent_term' => $sentenceTerm->sent_term,
                            'sent_translit' => $sentenceTerm->sent_translit,
                            'position' => $sentenceTerm->position,
                        ]
                    ];
                } else {
                    $terms[] = [
                        'pivot' => [
                            'sent_term' => $sentenceTerm->sent_term,
                            'sent_translit' => $sentenceTerm->sent_translit,
                            'position' => $sentenceTerm->position,
                        ]
                    ];
                }
            }

            $sentence->terms = $terms;
            $sentences[] = $sentence;
        }

        $dialog->sentences = $sentences;

        View::share('pageTitle', 'Dialogger: Edit Dialog');

        return view('dialogs.builder', [
            'layout' => 'app',
            'modelType' => 'dialog',
            'dialog' => $dialog,
        ]);
    }

    public function store(StoreDialogRequest $request): JsonResponse
    {
        $dialogData = $request->dialog;
        unset($dialogData['sentences']);

        $dialog = Dialog::create($dialogData);

        $this->linkSentences($dialog, $request->dialog['sentences']);

        return response()->json([
            'dialog' => $dialog,
        ]);
    }

    public function update(UpdateDialogRequest $request, Dialog $dialog): JsonResponse
    {
        $dialogData = $request->dialog;
        unset($dialogData['sentences']);

        $dialog->update($dialogData);

        $this->linkSentences($dialog, $request->dialog['sentences']);

        return response()->json([
            'dialog' => $dialog,
        ]);
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
            if (!in_array($sentence->id, array_column($sentences, 'id'))) {
                $sentence->update([
                    'dialog_id' => null,
                ]);
            }
        }
    }

    public function destroy(Request $request, Dialog $dialog): RedirectResponse|JsonResponse
    {
        $dialog->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
            ]);

        } else {
            $this->flasher->addSuccess(__('deleted', ['thing' => $dialog->title]));

            return to_route('dialogs.index');
        }
    }
}
