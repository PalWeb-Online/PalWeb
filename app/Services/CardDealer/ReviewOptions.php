<?php

namespace App\Services\CardDealer;

use App\Models\Deck;
use App\Models\User;

final readonly class ReviewOptions
{
    public function __construct(
        public string $scope,
        public ?int $deckId,
        public int $newLimit,
        public int $reviewLimit,
        public int $learningSteps,
        public bool $flipDefault,
    ) {
    }

    public static function forUser(User $user, ?Deck $deck = null): self
    {
        return new self(
            scope: $deck ? 'deck' : 'dictionary',
            deckId: $deck?->id,
            newLimit: $user->getSrsPreference('new_limit', config('preferences.srs.new_limit')),
            reviewLimit: $user->getSrsPreference('review_limit', config('preferences.srs.review_limit')),
            learningSteps: $user->getSrsPreference('learning_steps', config('preferences.srs.learning_steps')),
            flipDefault: $user->getSrsPreference('flip_default', config('preferences.srs.flip_default')),
        );
    }
}
