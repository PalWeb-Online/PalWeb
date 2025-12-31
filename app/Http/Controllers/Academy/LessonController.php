<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertLessonRequest;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Lesson $lesson): \Inertia\Response
    {
        Gate::authorize('view', $lesson);

        $lesson->load([
            'unit',
            'deck.scores',
            'deck.terms.pronunciations',
            'activity.scores',
            'dialog.sentences'
        ]);

        return Inertia::render('Academy/Lessons/Show', [
            'section' => 'academy',
            'unit' => $lesson->unit ? new UnitResource($lesson->unit) : null,
            'lesson' => new LessonResource($lesson),
        ]);
    }

    public function store(UpsertLessonRequest $request): RedirectResponse
    {
        $unit = $request->unit_id ? Unit::find($request->unit_id) : null;

        $request->merge([
            'global_position' => 'tmp-'.Str::uuid()
        ]);

        $lesson = Lesson::create($request->all());

        if ($unit) {
            LessonService::reorderUnitLessons($unit);

        } else {
            $lesson->update([
                'unit_position' => null,
                'global_position' => 'ex'.$lesson->id
            ]);
        }

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.lesson', $lesson);
    }

    public function update(UpsertLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $oldUnit = $lesson->unit;

        if (! $request->unit_id) {
            $request->merge([
                'unit_position' => null,
                'global_position' => 'ex'.$lesson->id,
            ]);

        } else {
            $incomingUnit = Unit::find($request->unit_id);

            if ($oldUnit && $incomingUnit && $oldUnit->id !== $incomingUnit->id) {
                $request->merge([
                    'unit_position' => null,
                    'global_position' => 'tmp-'.Str::uuid(),
                ]);
            }
        }

        $lesson->update($request->all());
        $lesson->refresh();

        $newUnit = $lesson->unit;

        if ($oldUnit && (! $newUnit || $oldUnit->id !== $newUnit->id)) {
            LessonService::reorderUnitLessons($oldUnit);
        }

        if ($newUnit) {
            LessonService::reorderUnitLessons($newUnit);
        }

        $lesson->refresh();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.lesson', $lesson);
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        DB::transaction(function () use ($lesson) {
            $unit = $lesson->unit;
            $lesson->delete();

            if ($unit) {
                LessonService::reorderUnitLessons($unit);
            }
        });

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $lesson->title])]);
        return to_route('lesson-planner.index');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:unit,deck,dialog',
            'q' => 'nullable|string|max:255',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
        ]);

        $type = $validated['type'];
        $q = $validated['q'] ?? '';
        $lessonId = $validated['lesson_id'] ?? null;

        switch ($type) {
            case 'unit':
                $builder = Unit::withCount('lessons')->having('lessons_count', '<', 9);
                $table = 'units';
                $foreignKey = 'unit_id';
                $titleColumn = 'title';
                break;

            case 'deck':
                $builder = Deck::query();
                $table = 'decks';
                $foreignKey = 'deck_id';
                $titleColumn = 'name';
                break;

            case 'dialog':
                $builder = Dialog::query();
                $table = 'dialogs';
                $foreignKey = 'dialog_id';
                $titleColumn = 'title';
                break;

            default:
                abort(400, 'Invalid type.');
        }

        $columns = [
            $table.'.id',
            $table.'.'.$titleColumn.' as title'
        ];

        if ($type === 'unit') {
            $builder->addSelect($columns);
        } else {
            $builder->select($columns);
        }

        if ($type !== 'unit' && $q !== '') {
            $builder->where(function ($query) use ($q, $table, $titleColumn) {
                $query->where($table.'.'.$titleColumn, 'like', $q.'%');
            });
        }

        if ($type !== 'unit') {
            $builder
                ->whereNotExists(function ($sub) use ($foreignKey, $table, $lessonId) {
                    $sub->select(DB::raw(1))
                        ->from('lessons')
                        ->whereColumn('lessons.'.$foreignKey, $table.'.id');

                    if ($lessonId) {
                        $sub->where('lessons.id', '!=', $lessonId);
                    }
                })
                ->limit(10);
        }

        $results = $builder
            ->orderBy($titleColumn)
            ->limit(10)
            ->get()
            ->map(function ($model) {
                return [
                    'id' => $model->id,
                    'title' => $model->title,
                ];
            })
            ->values();

        return response()->json([
            'data' => $results,
        ]);
    }
}
