<?php

namespace Laravel\PricingPlans\Contracts;

use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanSubscription;
use Laravel\PricingPlans\SubscriptionBuilder;
use Laravel\PricingPlans\SubscriptionUsageManager;

interface Subscriber
{
    /**
     * Get a subscription by name.
     *
     * @param  string $name Subscription name
     * @return PlanSubscription|null
     */
    public function subscription(string $name = 'default');

    /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription Subscription name
     * @param  string|null $planCode
     * @return bool
     */
    public function subscribed(string $subscription, string $planCode = null): bool;

    /**
     * Subscribe user to a new plan.
     *
     * @param string $subscription Subscription name
     * @param Plan $plan
     * @return SubscriptionBuilder
     */
    public function newSubscription(string $subscription, Plan $plan): SubscriptionBuilder;

    /**
     * Get subscription usage manager instance.
     *
     * @param string $subscription Subscription name
     * @return SubscriptionUsageManager
     */
    public function subscriptionUsage(string $subscription): SubscriptionUsageManager;
}
