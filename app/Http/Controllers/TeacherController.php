<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class TeacherController extends Controller
{
    public function store(StoreTeacherRequest $request, User $user): JsonResponse
    {
        Gate::authorize('create', [Teacher::class, $user]);

        $teacher = $user->teacher()->create($request->validated());

        return response()->json([
            'teacher' => new TeacherResource($teacher),
        ], 201);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);

        $teacher->update($request->validated());

        return response()->json([
            'teacher' => new TeacherResource($teacher->fresh()),
        ]);
    }

    public function destroy(Teacher $teacher): JsonResponse
    {
        try {
            Gate::authorize('delete', $teacher);

            DB::transaction(function () use ($teacher) {
                $teacher->delete();
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Teacher.', [
                'teacher_id' => $teacher->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Teacher profile.', $e);
        }
    }
}
