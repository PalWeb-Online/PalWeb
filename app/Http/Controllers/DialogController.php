<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDialogRequest;
use App\Http\Requests\UpdateDialogRequest;
use App\Http\Resources\DialogResource;
use App\Models\Dialog;
use App\Models\Sentence;
use App\Policies\TextPolicy;
use App\Traits\RedirectsToSubscribe;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

class DialogController extends Controller
{
    use RedirectsToSubscribe;

//    todo: rename TextPolicy to DialogPolicy
    public function __construct(
        protected TextPolicy $can,
        protected FlasherInterface $flasher
    ) {
    }

    public function index(Request $request): \Inertia\Response|RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Dialog Library');

        return $this->redirectToSubscribeOnFail(function () use ($auth) {
            $this->failIfFalse($this->can->viewIndex($auth));

            return Inertia::render('Academy/Dialogs/Index', [
                'section' => 'academy',
                'dialogs' => DialogResource::collection(Dialog::paginate(25)->onEachSide(1)),
            ]);
        });
    }

    public function show(Request $request, Dialog $dialog): \Inertia\Response|RedirectResponse
    {
        $auth = $request->user();

        View::share('pageTitle', 'Dialog: '.$dialog->title);

        return $this->redirectToSubscribeOnFail(function () use ($auth, $dialog) {
            $this->failIfFalse($this->can->viewText($auth));

            return Inertia::render('Academy/Dialogs/Show', [
                'section' => 'academy',
                'dialog' => new DialogResource($dialog->load('sentences')),
            ]);
        });
//        View::share('pageDescription',
//            'Explore our collection of Dialogs in Spoken Arabic! Ideal for language learners & enthusiasts of Palestinian Arabic to improve their listening comprehension, speaking ability & fluency!');
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/Dialogger', [
            'section' => 'academy',
            'mode' => 'dialog',
        ]);
    }

    public function edit(Dialog $dialog): \Inertia\Response
    {
        $dialog->load('sentences');

        return Inertia::render('Admin/Dialogger', [
            'section' => 'academy',
            'mode' => 'dialog',
            'stagedModel' => new DialogResource($dialog),
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
