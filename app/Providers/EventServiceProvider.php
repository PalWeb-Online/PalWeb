<?php

namespace App\Providers;

use App\Events\DeckBuilt;
use App\Events\ModelPinned;
use App\Events\PasswordChanged;
use App\Events\ProfileChanged;
use App\Listeners\AfterEmailVerified;
use App\Listeners\AfterPasswordReset;
use App\Listeners\AfterSubscriptionCancelled;
use App\Listeners\AfterSubscriptionCreated;
use App\Listeners\AwardDeckBuiltBadge;
use App\Listeners\AwardDeckPinnedBadge;
use App\Listeners\AwardProfileChangedBadge;
use App\Listeners\AwardSentencePinnedBadge;
use App\Listeners\AwardTermPinnedBadge;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Spark\Events\SubscriptionCancelled;
use Spark\Events\SubscriptionCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Listens for user registrations
        Registered::class => [
            SendEmailVerificationNotification::class,
            // AfterUserRegistered::class,
        ],

        // Listens for password resets
        PasswordReset::class => [
            AfterPasswordReset::class,
        ],

        // Listens for password changed
        PasswordChanged::class => [
            AfterPasswordReset::class,
        ],

        // Listens for subscriptions that get created
        SubscriptionCreated::class => [
            AfterSubscriptionCreated::class,
        ],

        // Listens for subscriptions that get cancelled
        SubscriptionCancelled::class => [
            AfterSubscriptionCancelled::class,
        ],

        // Listens for users that have verified their email address
        Verified::class => [
            AfterEmailVerified::class,
        ],

        ModelPinned::class => [
            AwardDeckPinnedBadge::class,
            AwardTermPinnedBadge::class,
            AwardSentencePinnedBadge::class,
        ],

        DeckBuilt::class => [
            AwardDeckBuiltBadge::class
        ],

        ProfileChanged::class => [
            AwardProfileChangedBadge::class,
        ],

        SocialiteWasCalled::class => [
            DiscordExtendSocialite::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
