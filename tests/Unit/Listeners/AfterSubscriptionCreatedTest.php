<?php

namespace Tests\Unit\Listeners;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Subscription;
use Spark\Events\SubscriptionCreated;
use Tests\TestCase;

class AfterSubscriptionCreatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_after_subscription_created_adds_user_to_student_role(): void
    {
        $this->roles();

        $user = User::factory()->create();
        $this->assertFalse($user->hasRole('student'));
        $subscription = new Subscription;
        event(new SubscriptionCreated($user, $subscription));

        $user->refresh();
        $this->assertTrue($user->hasRole('student'));
    }

    public function test_after_subscription_created_if_user_is_already_a_student(): void
    {
        $this->roles();

        // Should just do nothing basically
        $user = User::factory()->create()->grantStudentRole();
        $this->assertTrue($user->hasRole('student'));
        $subscription = new Subscription;
        event(new SubscriptionCreated($user, $subscription));

        $user->refresh();
        $this->assertTrue($user->hasRole('student'));
    }

    public function test_after_subscription_created_if_user_is_an_admin(): void
    {
        $this->roles();

        $user = User::factory()->create()->grantAdminRole();
        $this->assertTrue($user->hasRole('admin'));
        $subscription = new Subscription;
        event(new SubscriptionCreated($user, $subscription));

        $user->refresh();
        $this->assertTrue($user->hasRole('student'));
    }
}
