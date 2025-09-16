<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DialogResource;
use App\Http\Resources\ScoreResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Score;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('Academy/Scores/Index', [
            'section' => 'academy',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScoreRequest $request): RedirectResponse
    {
        Score::create(array_merge($request->all(), [
            'user_id' => $request->user()->id,
        ]));

        session()->flash('notification',
            ['type' => 'success', 'message' => 'Your Score for this Quiz has been saved!']);

        return to_route('quizzer.show', [
            'scorable_type' => $request->scorable_type,
            'scorable_id'   => $request->scorable_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score): \Inertia\Response
    {
        $scorable = $score->scorable->load(['scores']);

        $modelResource = $scorable instanceof Deck
            ? new DeckResource($scorable)
            : new DialogResource($scorable);

        return Inertia::render('Academy/Scores/Show', [
            'section' => 'academy',
            'model' => $modelResource,
            'score' => new ScoreResource($score)
        ]);
    }

    public function history(string $scorable_type, int $scorable_id): \Inertia\Response
    {
        $modelClass = match ($scorable_type) {
            'deck' => Deck::class,
            'dialog' => Dialog::class,
            default => abort(404),
        };

        $model = $modelClass::findOrFail($scorable_id);
        $model->load(['scores']);

        $scores = $model->scores()->paginate(10)->onEachSide(1);
        $totalCount = $scores->total();

        return Inertia::render('Academy/Scores/History', [
            'section' => 'academy',
            'model' => $scorable_type === 'deck' ? new DeckResource($model) : new DialogResource($model),
            'scorable_type' => $scorable_type,
            'scores' => ScoreResource::collection($scores),
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score): RedirectResponse
    {
        $score->delete();
        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => 'Score'])]);

        return to_route('scores.history', [
            'scorable_type' => $score->scorable_type,
            'scorable_id'   => $score->scorable_id,
        ]);
    }
}
