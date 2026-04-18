<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\DeckResource;
use App\Models\Card;
use App\Models\Deck;
use App\Models\Lesson;
use App\Models\Term;
use App\Services\CardDealer\ReviewOptions;
use App\Services\CardDealer\ReviewService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardDealerController extends Controller
{
    public function index(Request $request, ReviewService $reviewService): \Inertia\Response
    {
        $user = auth()->user();
        $options = $this->makeReviewOptions($request, $user);

        $deck = $options->scope === 'deck'
            ? Deck::findOrFail($options->deckId)
            : null;

        $reviewHistory = $user->cardReviews()
            ->where('reviewed_at', '>=', now()->subDays(30))
            ->selectRaw("
                reviewed_at,
                count(distinct case when type in ('learning', 'graduating') then card_id end) as new_cards,
                count(distinct case when type = 'review' then card_id end) as owned_cards,
                count(case when type = 'learning' then 1 end) as learning_steps,
                count(case when type = 'relearning' then 1 end) as relearning_steps,
                count(case when type in ('review', 'graduating') and grade > 0 then 1 end) as right_reviews,
                count(case when type = 'review' and grade = 0 then 1 end) as wrong_reviews,
                sum(seconds_spent) as total_seconds
            ")
            ->groupBy('reviewed_at')
            ->get()
            ->keyBy(fn ($item) => $item->reviewed_at->format('Y-m-d'));

        return Inertia::render('Workbench/CardDealer/Index', [
            'section' => 'academy',
            'scope' => $options->scope,
            'deck' => $deck ? new DeckResource($deck) : null,
            'cards' => CardResource::collection(Card::forUser($user->id)->get()),
            'terms_count' => Term::count(),
            'review_history' => $reviewHistory,
            'session_stats' => $reviewService->getSessionStats($user, ReviewOptions::forUser($user)),
        ]);
    }

    public function review(Request $request, ReviewService $reviewService): \Inertia\Response|RedirectResponse
    {
        $user = auth()->user();
        $options = $this->makeReviewOptions($request, $user);

        $deck = $options->scope === 'deck'
            ? Deck::findOrFail($options->deckId)
            : null;

        $cards = $reviewService->buildSession($user, $options);

        if ($cards->isEmpty()) {
            session()->flash('notification', [
                'type' => 'info',
                'message' => 'There were no Cards to review.',
            ]);

            return redirect()->route('card-dealer.index');

        } else {
            return Inertia::render('Workbench/CardDealer/Review', [
                'section' => 'academy',
                'deck' => $deck ? new DeckResource($deck) : null,
                'cards' => CardResource::collection($cards),
                'options' => $options,
            ]);
        }
    }

    public function cards(Request $request): \Inertia\Response
    {
        $filters = array_merge(['sort' => 'due'], $request->only(['status', 'level', 'sort']));

        $cards = Card::query()
            ->forUser(auth()->id())
            ->with(['term.root', 'term.glosses', 'term.pronunciations'])
            ->filterStatus($filters['status'] ?? null)
            ->filterLevel($filters['level'] ?? null)
            ->sort($filters['sort'] ?? 'due')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Workbench/CardDealer/Cards', [
            'section' => 'academy',
            'filters' => $filters,
            'cards' => CardResource::collection($cards),
            'totalCount' => $cards->total(),
        ]);
    }

    public function getScopeCards(Request $request, ReviewService $reviewService): JsonResponse
    {
        try {
            $user = auth()->user();
            $options = $this->makeReviewOptions($request, $user);

            $count = 0;
            if ($options->scope === 'deck') {
                $count += Deck::findOrFail($options->deckId)?->terms_count;

            } elseif ($options->scope === 'pinned') {
                 $decks = Deck::whereHasBookmark($user)->get();

                 foreach ($decks as $deck) {
                     $count += $deck->terms_count;
                 }

            } elseif ($options->scope === 'lesson') {
                $lessons = Lesson::whereHas('users', fn (Builder $userQuery) => $userQuery->whereKey($user->id))->get();

                foreach ($lessons as $lesson) {
                    $count += $lesson->deck->terms_count;
                }
            }

            $cards = Card::query()
                ->forUser($user->id)
                ->forReviewOptions($options, $user)
                ->get();

            return response()->json([
                'cards' => CardResource::collection($cards),
                'terms_count' => $count,
                'session_stats' => $reviewService->getSessionStats($user, $options),
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Deck Cards.'], 500);
        }
    }

    private function makeReviewOptions(Request $request, $user): ReviewOptions
    {
        $scope = $request->string('scope')->toString() ?: 'all';
        $deckId = $request->integer('deck');

        $deck = $scope === 'deck' && $deckId
            ? Deck::findOrFail($deckId)
            : null;

        return ReviewOptions::forUser($user, $scope, $deck);
    }
}
