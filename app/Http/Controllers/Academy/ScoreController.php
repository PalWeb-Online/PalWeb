<?php

namespace App\Http\Controllers\Academy;

use App\Events\ScoreCreated;
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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $latestScores = Score::query()
            ->whereIn('id', function ($query) use ($request) {
                $query->selectRaw('max(id)')
                    ->from((new Score)->getTable())
                    ->where('user_id', $request->user()->id)
                    ->groupBy('scorable_type', 'scorable_id');
            })
            ->with('scorable')
            ->latest()
            ->get();

        $latestScoredDecks = [];

        foreach ($latestScores as $score) {
            $scorable = $score->scorable;
            $scorable->load('scores');
            $latestScoredDecks[] = $scorable;
        }

        $totalCount = count($latestScoredDecks);
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $decks = collect($latestScoredDecks)->forPage($currentPage, $perPage);

        $decks = new LengthAwarePaginator(
            $decks,
            $totalCount,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return Inertia::render('Academy/Scores/Index', [
            'section' => 'academy',
            'decks' => DeckResource::collection($decks),
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScoreRequest $request): RedirectResponse
    {
        $score = Score::create(array_merge($request->all(), [
            'user_id' => $request->user()->id,
        ]));

        ScoreCreated::dispatch($score);

        session()->flash('notification',
            ['type' => 'success', 'message' => 'Your Score for this model has been saved!']);

        return to_route('deck-master.study', $request->scorable_id);
    }

    public function history(Request $request, string $scorable_type, int $scorable_id): \Inertia\Response
    {
        $request->validate([
            'score' => ['nullable', 'integer', 'exists:scores,id']
        ]);

        $modelClass = match ($scorable_type) {
            'deck' => Deck::class,
            'dialog' => Dialog::class,
            default => abort(404),
        };

        $model = $modelClass::findOrFail($scorable_id);
        $model->load(['scores']);

        $scores = $model->scores()->paginate(10)->onEachSide(1);
        $totalCount = $scores->total();

        $selectedScore = null;
        if ($request->filled('score')) {
            $selectedScore = Score::with('scorable')->findOrFail($request->input('score'));
        }

        return Inertia::render('Academy/Scores/History', [
            'section' => 'academy',
            'model' => $scorable_type === 'deck' ? new DeckResource($model) : new DialogResource($model),
            'scorable_type' => $scorable_type,
            'scores' => ScoreResource::collection($scores),
            'totalCount' => $totalCount,
            'selectedScore' => $selectedScore ? new ScoreResource($selectedScore) : null,
        ]);
    }

    public function purge(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scorable_type' => ['required', Rule::in(['deck', 'dialog'])],
            'scorable_id' => ['required', 'integer'],
            'older_than' => ['nullable', Rule::in(['day', 'week', 'month', 'year'])],
            'except' => ['nullable', 'array'],
            'except.*' => ['string', Rule::in(['highest', 'latest'])],
        ]);

        $modelClass = match ($validated['scorable_type']) {
            'deck' => Deck::class,
            'dialog' => Dialog::class,
        };
        $model = $modelClass::findOrFail($validated['scorable_id']);

        $query = $model->scores()->where('user_id', $request->user()->id);

        if ($request->filled('older_than')) {
            $date = match ($validated['older_than']) {
                'day' => Carbon::now()->subDay(),
                'week' => Carbon::now()->subWeek(),
                'month' => Carbon::now()->subMonth(),
                'year' => Carbon::now()->subYear(),
            };
            $query->where('created_at', '<', $date);
        }

        $exceptions = [];
        if ($request->filled('except')) {
            if (in_array('latest', $validated['except'])) {
                $latestScore = $model->scores()->where('user_id', $request->user()->id)->first();
                if ($latestScore) {
                    $exceptions[] = $latestScore->id;
                }
            }
            if (in_array('highest', $validated['except'])) {
                $highestScore = $model->scores()->where('user_id',
                    $request->user()->id)->reorder()->orderByDesc('score')->first();
                if ($highestScore) {
                    $exceptions[] = $highestScore->id;
                }
            }
        }

        if (! empty($exceptions)) {
            $query->whereNotIn('id', array_unique($exceptions));
        }

        $count = $query->count();
        $query->delete();

        session()->flash('notification',
            ['type' => 'success', 'message' => "Successfully purged {$count} Scores."]);

        return to_route('scores.history', [
            'scorable_type' => $validated['scorable_type'],
            'scorable_id' => $validated['scorable_id'],
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
            'scorable_id' => $score->scorable_id,
        ]);
    }
}
