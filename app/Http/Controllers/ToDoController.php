<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackCommentRequest;
use App\Models\Gloss;
use App\Models\Inflection;
use App\Models\FeedbackComment;
use App\Models\Term;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ToDoController extends Controller
{
    public function index(): \Inertia\Response
    {
        $termsMissingSentences = [];

        Gloss::chunk(200, function ($glosses) use (&$termsMissingSentences) {
            foreach ($glosses as $gloss) {
                if (count($gloss->term->sentences($gloss->id)->get()) < 1) {
                    $gloss->term->gloss = $gloss->gloss;
                    $termsMissingSentences[] = $gloss->term;
                }

                if (count($termsMissingSentences) === 100) {
                    return false;
                }
            }
        });

        $missingInflections = [];
        foreach (Inflection::whereIn('form', ['ap', 'pp', 'nv'])->get() as $inflection) {
            if (Term::where('translit', $inflection->translit)->doesntExist()) {
                $missingInflections[] = $inflection;
            }
        }
        $missingInflections = collect($missingInflections);

        return Inertia::render('Admin/ToDo', [
            'feedbackComments' => FeedbackComment::all(),
            'fromSentences' => DB::table('sentence_term')->whereNull('term_id')->get(),
            'missingInflections' => $missingInflections,
            'termsMissingSentences' => $termsMissingSentences,
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
        return to_route('missing.terms.index');
    }
}
