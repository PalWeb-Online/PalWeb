<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function update(Request $request, Card $card)
    {
        $grade = $request->integer('grade');
        $secondSpent = $request->integer('seconds_spent');
        $nextInterval = $request->integer('next_interval');
        $maxSteps = $request->integer('learning_steps');

        $card->review($grade, $secondSpent, $nextInterval, $maxSteps);

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'next_due' => $card->due_at,
        ]);
    }

    public function master(Request $request, Card $card)
    {
        $card->master();

        if ($request->header('X-Inertia')) {
            return back();
        }

        return response()->json([
            'success' => true,
            'message' => 'Card mastered.',
        ]);
    }

    public function suspend(Request $request, Card $card)
    {
        $card->suspended_at ? $card->restore() : $card->suspend();

        if ($request->header('X-Inertia')) {
            return back();
        }

        return response()->json([
            'success' => true,
            'message' => 'Card suspended.',
        ]);
    }

    public function reset(Request $request, Card $card)
    {
        $card->reset();

        if ($request->header('X-Inertia')) {
            return back();
        }

        return response()->json([
            'success' => true,
            'message' => 'Card reset.',
        ]);
    }

    public function destroy(Card $card): RedirectResponse
    {
        $card->delete();

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => 'Card'])]);

        return back();
    }

    public function purge(): RedirectResponse
    {
        $count = Card::purgeForUser(auth()->id());

        session()->flash('notification', [
            'type' => 'success',
            'message' => "Purged {$count} New Cards."
        ]);

        return back();
    }
}
