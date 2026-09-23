<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Score;
use App\Models\User;
use App\Services\CardDealer\ReviewOptions;
use App\Services\CardDealer\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AcademyStateController extends Controller
{
    public function show(Request $request, ReviewService $reviewService): JsonResponse
    {
        $user = $request->user();

        $currentLesson = $this->currentLesson($user);
        $lessonProgress = $user->getLessonProgress();

        $remainingDue = (int) $reviewService->getSessionStats($user, ReviewOptions::forUser($user))['remaining_due'];

        return response()->json([
            'study_plan' => [
                'date' => today()->toDateString(),
                'cards' => [
                    'remaining_due' => $remainingDue,
                    'completed' => $remainingDue === 0,
                    'route' => route('card-dealer.review'),
                ],
                'lesson' => $currentLesson ? $this->lessonTask($user, $currentLesson) : null,
            ],
            'current_lesson' => $currentLesson ? $this->formatLesson($currentLesson, $user) : null,
            'lesson_progress' => $lessonProgress,
            'unlocked_lessons' => array_keys($lessonProgress),
        ]);
    }

    private function lessonTask(User $user, Lesson $currentLesson): array
    {
        $assignment = Cache::get($this->cacheKey($user));

        if (! $this->isValidLessonTask($user, $assignment)) {
            $assignment = $this->makeLessonTask($user, $currentLesson);
            Cache::put($this->cacheKey($user), $assignment, now()->endOfDay());
        }

        return $this->formatLessonTask($assignment, $user);
    }

    private function makeLessonTask(User $user, Lesson $lesson): array
    {
        $progress = $lesson->getProgressFor($user);

        $stage = (int) ($progress['stage'] ?? 1);

        if ($stage <= 1) {
            return [
                'key' => 'deck',
                'lesson_id' => $lesson->id,
                'model_type' => 'deck',
                'model_id' => $lesson->deck_id,
            ];
        }

        if ($stage === 2) {
            return [
                'key' => 'activity',
                'lesson_id' => $lesson->id,
                'model_type' => 'activity',
                'model_id' => $lesson->activity_id,
            ];
        }

        return [];
    }

    private function formatLessonTask(array $assignment, User $user): array
    {
        $lesson = Lesson::with(['deck', 'activity'])->find($assignment['lesson_id']);

        $modelType = $assignment['model_type'] ?? null;
        $modelId = $assignment['model_id'] ?? null;

        $completed = $modelType && $modelId && Score::query()
                ->where('scorable_type', $modelType)
                ->where('scorable_id', $modelId)
                ->where('score', '>=', 1)
                ->whereDate('created_at', today())
                ->exists();

        return [
            'key' => $assignment['key'],
            'completed' => $completed,
            'lesson' => $lesson ? $this->formatLesson($lesson, $user) : null,
            'action' => [
                'model_type' => $modelType,
                'model_id' => $modelId,
                'route' => $this->actionRoute($assignment, $lesson),
            ],
        ];
    }

    private function actionRoute(array $assignment, ?Lesson $lesson): ?string
    {
        if (! $lesson) {
            return null;
        }

        return match ($assignment['key'] ?? null) {
            'deck' => $lesson->deck_id ? route('deck-master.study', $lesson->deck_id) : null,
            'activity' => $lesson->activity_id ? route('activities.activity', $lesson->activity_id) : null,
            default => null,
        };
    }

    private function isValidLessonTask(User $user, mixed $assignment): bool
    {
        if (! is_array($assignment) || ! isset($assignment['key']) || ! isset($assignment['lesson_id'])) {
            return false;
        }

        $lessonId = $assignment['lesson_id'];

        return Lesson::whereKey($lessonId)->exists() && isset($user->getLessonProgress()[$lessonId]);
    }

    private function currentLesson(User $user): ?Lesson
    {
        $incompleteLessonIds = collect($user->getLessonProgress())
            ->filter(fn (array $progress) => ! ($progress['completed'] ?? false))
            ->keys()
            ->all();

        if (empty($incompleteLessonIds)) {
            return null;
        }

        $query = Lesson::with(['unit', 'deck', 'activity', 'dialog']);

        if (! $user->isAdmin()) {
            $query->whereIn('id', $incompleteLessonIds);
        }

        return $query
            ->orderBy('global_position')
            ->first();
    }

    private function formatLesson(Lesson $lesson, ?User $user = null): array
    {
        return [
            'id' => $lesson->id,
            'global_position' => $lesson->global_position,
            'title' => $lesson->title,
            'progress' => $user ? $lesson->getProgressFor($user) : null,
        ];
    }

    private function cacheKey(User $user): string
    {
        return "academy-progress:{$user->id}:".today()->toDateString();
    }
}
