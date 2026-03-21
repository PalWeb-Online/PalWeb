<?php

namespace App\Services\CardDealer;

use App\Models\Card;
use App\Models\CardReview;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ReviewService
{
    public function buildSession(User $user, ReviewOptions $options): Collection
    {
        $ownedReviewedToday = $this->countOwnedReviewedToday($user);
        $sessionCapacity = max(0, $options->reviewLimit - $ownedReviewedToday);

        $dueQuery = $this->baseDueCardsQuery($user, $options);
        if ($options->promptType === 'audio') {
            $dueQuery->whereHas('term', fn (Builder $query) => $query->hasFluentAudio());
        }

        $ownedDueCards = (clone $dueQuery)
            ->owned()
            ->with(['term'])
            ->limit($sessionCapacity)
            ->get();

        $remainingSessionCapacity = max(0, $sessionCapacity - $ownedDueCards->count());

        $existingNewDueCards = collect();
        if ($remainingSessionCapacity > 0) {
            $existingNewDueCards = (clone $dueQuery)
                ->new()
                ->with(['term'])
                ->limit(min($remainingSessionCapacity, $options->newLimit))
                ->get();
        }

        $remainingSessionCapacity = max(0, $remainingSessionCapacity - $existingNewDueCards->count());

        $newReviewedToday = $this->countNewReviewedToday($user);

        $remainingNewLimit = max(0,
            $options->newLimit - $existingNewDueCards->count() - $newReviewedToday
        );

        $newTerms = $remainingSessionCapacity > 0 && $remainingNewLimit > 0
            ? Term::query()
                ->inRandomOrder()
                ->forReviewOptions($options)
                ->when($options->promptType === 'audio', fn (Builder $query) => $query->hasFluentAudio())
                ->whereDoesntHave('cards', fn ($q) => $q->where('user_id', $user->id))
                ->limit(min($remainingSessionCapacity, $remainingNewLimit))
                ->get()
            : collect();

        $newCards = $newTerms->map(fn ($term) => Card::createForUserAndTerm($user, $term));

        $cards = $ownedDueCards
            ->concat($existingNewDueCards)
            ->concat($newCards);

        $cards->each(function ($card) use ($options) {
            $card->next_intervals = [
                0 => $card->calculateNextInterval(0, $options->learningSteps),
                1 => $card->calculateNextInterval(1, $options->learningSteps),
                2 => $card->calculateNextInterval(2, $options->learningSteps),
                3 => $card->calculateNextInterval(3, $options->learningSteps),
            ];
        });

        return $cards->shuffle()->values();
    }

    public function getSessionStats(User $user, ReviewOptions $options): array
    {
        $ownedReviewedToday = $this->countOwnedReviewedToday($user);
        $newReviewedToday = $this->countNewReviewedToday($user);

        $dueQuery = $this->baseDueCardsQuery($user, $options);

        $remainingDue = (clone $dueQuery)->count();
        $remainingNew = (clone $dueQuery)->where('repetitions', 0)->count();
        $remainingReviews = (clone $dueQuery)->where('repetitions', '>', 0)->count();

        $availableNew = $this->countAvailableNewTerms($user, $options);

        return [
            'owned_reviewed_today' => $ownedReviewedToday,
            'new_reviewed_today' => $newReviewedToday,
            'remaining_due' => $remainingDue,
            'remaining_reviews' => $remainingReviews,
            'remaining_new' => $remainingNew,
            'available_new' => $availableNew,
        ];
    }

    private function countOwnedReviewedToday(User $user): int
    {
        return CardReview::query()
            ->where('user_id', $user->id)
            ->whereIn('type', ['review', 'graduating'])
            ->whereDate('reviewed_at', today())
            ->distinct('card_id')
            ->count('card_id');
    }

    private function countNewReviewedToday(User $user): int
    {
        return CardReview::query()
            ->where('user_id', $user->id)
            ->where('type', 'graduating')
            ->whereDate('reviewed_at', today())
            ->distinct('card_id')
            ->count('card_id');
    }

    private function countAvailableNewTerms(User $user, ReviewOptions $options): int
    {
        return Term::query()
            ->forReviewOptions($options)
            ->whereDoesntHave('cards', fn ($q) => $q->where('user_id', $user->id))
            ->count();
    }

    private function baseDueCardsQuery(User $user, ReviewOptions $options): Builder
    {
        return Card::query()
            ->forUser($user->id)
            ->forReviewOptions($options)
            ->active()
            ->due()
            ->orderByRaw("
                case
                    when step is not null and repetitions > 0 then 0
                    when step is null and due_at < curdate() then 1
                    when step is null and due_at = curdate() then 2
                    when step is not null and repetitions = 0 then 3
                    else 4
                end
            ")
            ->orderBy('due_at');
    }
}
