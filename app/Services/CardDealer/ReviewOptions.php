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
        public string $promptType,
    ) {
    }

    public static function forUser(User $user, string $scope = 'all', ?Deck $deck = null): self
    {
        return new self(
            scope: $scope,
            deckId: $scope === 'deck' ? $deck?->id : null,
            newLimit: $user->getSrsPreference('new_limit', config('preferences.srs.new_limit')),
            reviewLimit: $user->getSrsPreference('review_limit', config('preferences.srs.review_limit')),
            learningSteps: $user->getSrsPreference('learning_steps', config('preferences.srs.learning_steps')),
            promptType: $user->getSrsPreference('prompt_type', config('preferences.srs.prompt_type')),
        );
    }
}
