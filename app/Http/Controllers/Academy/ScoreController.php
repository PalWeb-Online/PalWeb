<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\ScoreResource;
use App\Models\Deck;
use App\Models\Score;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function store(Request $request): RedirectResponse
    {
        Score::create([
            'user_id' => $request->user()->id,
            'scorable_type' => Deck::class,
            'scorable_id' => $request->input('scorable_id'),
            'settings' => $request->input('settings'),
            'score' => $request->input('score'),
            'results' => $request->input('results'),
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score): \Inertia\Response
    {
        $deck = $score->scorable->load(['scores']);

        return Inertia::render('Academy/Scores/Show', [
            'section' => 'academy',
            'model' => new DeckResource($deck),
            'score' => new ScoreResource($score)
        ]);
    }

    public function historyDeck(Deck $deck): \Inertia\Response
    {
        $deck->load(['scores']);

        return Inertia::render('Academy/Scores/History', [
            'section' => 'academy',
            'model' => new DeckResource($deck),
        ]);
    }

//    public function historyDialog(Dialog $dialog): \Inertia\Response
//    {
//        return Inertia::render('Academy/Scores/History', [
//            'section' => 'academy',
//            'model' => new DialogResource($dialog),
//        ]);
//    }
//
//    public function historySkill(Skill $skill): \Inertia\Response
//    {
//        return Inertia::render('Academy/Scores/History', [
//            'section' => 'academy',
//            'model' => new SkillResource($skill),
//        ]);
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score): RedirectResponse
    {
//        $this->authorize('modify', $deck);

        $score->delete();
        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => 'Score'])]);

        return redirect()->back();
    }
}
