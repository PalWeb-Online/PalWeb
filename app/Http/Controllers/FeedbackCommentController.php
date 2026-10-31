<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\FeedbackComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class FeedbackCommentController extends Controller
{
    public function index(): JsonResponse
    {
        $comments = FeedbackComment::with('user')->get();

        return response()->json([
            'feedbackComments' => CommentResource::collection($comments),
        ]);
    }

    public function store(StoreFeedbackCommentRequest $request): JsonResponse
    {
        FeedbackComment::create([
            'comment' => $request['comment'],
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(FeedbackComment $feedbackComment): JsonResponse
    {
        try {
            Gate::authorize('delete', $feedbackComment);

            $deletedComment = $feedbackComment->comment;

            DB::transaction(function () use ($feedbackComment) {
                $feedbackComment->delete();
            });

            return response()->json([
                'success' => true,
                'message' => __('deleted', ['thing' => $deletedComment]),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Comment.', [
                'feedback_comment_id' => $feedbackComment->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Feedback Comment.', $e);
        }
    }
}
