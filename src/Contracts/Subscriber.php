<?php

namespace Laravel\PricingPlans\Contracts;

use Laravel\PricingPlans\Models\Plan;

interface Subscriber
{
    /**
     * Get a subscription by name.
     *
     * @param  string $name Subscription name
     * @return \Laravel\PricingPlans\Models\PlanSubscription|null
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
     * @param \Laravel\PricingPlans\Models\Plan $plan
     * @return \Laravel\PricingPlans\SubscriptionBuilder
     */
    public function newSubscription(string $subscription, Plan $plan);

    /**
     * Get subscription usage manager instance.
     *
     * @param string $subscription Subscription name
     * @return \Laravel\PricingPlans\SubscriptionUsageManager
     */
    public function subscriptionUsage(string $subscription);
}
