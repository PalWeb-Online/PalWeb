<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\FeedbackComment;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

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

    public function destroy(FeedbackComment $feedbackComment): RedirectResponse
    {
        $feedbackComment->delete();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $feedbackComment->comment])]);
        return to_route('feedback.index');
    }
}
