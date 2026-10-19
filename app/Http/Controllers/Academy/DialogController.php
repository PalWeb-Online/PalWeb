<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertDialogRequest;
use App\Http\Resources\DialogResource;
use App\Models\Dialog;
use App\Models\Sentence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class DialogController extends Controller
{
    public function index(): JsonResponse
    {
        $dialogs = Dialog::paginate(25)->onEachSide(1);
        $resource = DialogResource::collection($dialogs);

        return response()->json([
            'dialogs' => [
                'data' => $resource->toArray(request()),
                'meta' => [
                    'links' => $dialogs->linkCollection()->toArray(),
                    'current_page' => $dialogs->currentPage(),
                    'last_page' => $dialogs->lastPage(),
                    'total' => $dialogs->total(),
                ],
            ],
        ]);
    }

    public function show(Dialog $dialog): JsonResponse
    {
        Gate::authorize('view', $dialog);

        return $this->fetchDialog($dialog);
    }

    public function edit(Dialog $dialog): JsonResponse
    {
        Gate::authorize('view', $dialog);

        return $this->fetchDialog($dialog);
    }

    private function fetchDialog(Dialog $dialog): JsonResponse
    {
        $dialog->load('sentences');

        return response()->json([
            'dialog' => new DialogResource($dialog),
        ]);
    }

    public function store(UpsertDialogRequest $request): JsonResponse
    {
        $dialog = Dialog::create($request->all());
        $this->linkSentences($dialog, $request->sentences);

        return response()->json([
            'deck' => new DialogResource($dialog),
        ], 201);
    }

    public function update(UpsertDialogRequest $request, Dialog $dialog): JsonResponse
    {
        $dialog->update($request->all());
        $this->linkSentences($dialog, $request->sentences);

        return response()->json([
            'deck' => new DialogResource($dialog),
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
            if (! in_array($sentence->id, array_column($sentences, 'id'))) {
                $sentence->update([
                    'dialog_id' => null,
                ]);
            }
        }
    }

    public function destroy(Dialog $dialog): JsonResponse
    {
        try {
            Gate::authorize('delete', $dialog);

            DB::transaction(function () use ($dialog) {
                $dialog->delete();
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Dialog.', [
                'dialog_id' => $dialog->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Dialog.', $e);
        }
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
        ]);

        $q = trim($validated['q'] ?? '');
        $lessonId = $validated['lesson_id'] ?? null;

        $dialogs = Dialog::query()
            ->select([
                'dialogs.id',
                'dialogs.title',
                'dialogs.published',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('dialogs.title', 'like', $q.'%');
            })
            ->whereNotExists(function ($sub) use ($lessonId) {
                $sub->select(DB::raw(1))
                    ->from('lessons')
                    ->whereColumn('lessons.dialog_id', 'dialogs.id');

                if ($lessonId) {
                    $sub->where('lessons.id', '!=', $lessonId);
                }
            })
            ->orderBy('dialogs.title')
            ->limit(10)
            ->get()
            ->map(fn (Dialog $dialog) => [
                'id' => $dialog->id,
                'title' => $dialog->title,
                'published' => (bool) $dialog->published,
            ])
            ->values();

        return response()->json([
            'results' => $dialogs,
        ]);
    }
}
