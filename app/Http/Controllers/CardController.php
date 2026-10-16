<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class CardController extends Controller
{
    public function update(Request $request, Card $card): JsonResponse
    {
        Gate::authorize('update', $card);

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

    public function master(Card $card): JsonResponse
    {
        Gate::authorize('update', $card);

        $card->master();

        return response()->json([
            'success' => true,
        ]);
    }

    public function suspend(Card $card): JsonResponse
    {
        Gate::authorize('update', $card);

        $card->suspended_at ? $card->restore() : $card->suspend();

        return response()->json([
            'success' => true,
        ]);
    }

    public function reset(Card $card): JsonResponse
    {
        Gate::authorize('update', $card);

        $card->reset();

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(Card $card): JsonResponse
    {
        try {
            Gate::authorize('delete', $card);

            DB::transaction(function () use ($card) {
                $card->delete();
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Card.', [
                'card_id' => $card->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Card.', $e);
        }
    }

    public function purge(): JsonResponse
    {
        $count = Card::purgeForUser(auth()->id());

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
