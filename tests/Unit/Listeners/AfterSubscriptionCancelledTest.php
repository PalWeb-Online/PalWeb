<?php

namespace Tests\Unit\Listeners;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Subscription;
use Spark\Events\SubscriptionCancelled;
use Tests\TestCase;

class AfterSubscriptionCancelledTest extends TestCase
{
    use RefreshDatabase;

    public function test_after_subscription_created_removes_user_from_student_role(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($user->hasRole('student'));
        $subscription = new Subscription;
        event(new SubscriptionCancelled($user, $subscription));

        $user->refresh();
        $this->assertFalse($user->hasRole('student'));
    }

    public function test_after_subscription_created_if_user_not_a_student(): void
    {
        $this->roles();

        $user = User::factory()->create();
        $this->assertFalse($user->hasRole('student'));
        $subscription = new Subscription;
        event(new SubscriptionCancelled($user, $subscription));

        $user->refresh();
        $this->assertFalse($user->hasRole('student'));
    }

    public function test_after_subscription_created_if_user_is_an_admin(): void
    {
        $this->roles();

        $user = User::factory()->create();
        $this->assertFalse($user->hasRole('student'));
        $subscription = new Subscription;
        event(new SubscriptionCancelled($user, $subscription));

        $user->refresh();
        $this->assertFalse($user->hasRole('student'));
    }
}
