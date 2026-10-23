<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class CardController extends Controller
{
    public function __construct(
        protected TermService $termService,
    ) {
    }

    public function cards(Request $request): JsonResponse
    {
        $filters = array_merge(['sort' => 'due'], $request->only(['status', 'level', 'sort']));

        $cards = Card::query()
            ->forUser(auth()->id())
            ->with([
                'term' => fn ($q) => $q->withItemData(),
            ])
            ->filterStatus($filters['status'] ?? null)
            ->filterLevel($filters['level'] ?? null)
            ->sort($filters['sort'] ?? 'due')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $terms = $cards
            ->getCollection()
            ->pluck('term')
            ->filter();

        $this->termService->hydratePronunciations($terms);
        $cardResource = CardResource::collection($cards);

        return response()->json([
            'filters' => $filters,
            'cards' => [
                'data' => $cardResource->toArray($request),
                'meta' => [
                    'links' => $cards->linkCollection()->toArray(),
                    'current_page' => $cards->currentPage(),
                    'last_page' => $cards->lastPage(),
                    'total' => $cards->total(),
                ],
            ],
            'totalCount' => $cards->total(),
        ]);
    }

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
