<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\FeedbackComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class FeedbackCommentController extends Controller
{
    public function index(): \Inertia\Response
    {
        $comments = FeedbackComment::with('user')->get();

        return Inertia::render('Office/ViewFeedback', [
            'section' => 'office',
            'feedbackComments' => CommentResource::collection($comments),
        ]);
    }

    public function store(StoreFeedbackCommentRequest $request): RedirectResponse
    {
        FeedbackComment::create([
            'comment' => $request['comment'],
            'user_id' => auth()->id(),
        ]);

        session()->flash('notification',
            ['type' => 'success', 'message' => 'Thank you for your feedback!']);
        return back();
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
