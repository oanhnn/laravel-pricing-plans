<?php

namespace Laravel\PricingPlans\Contracts;

use Laravel\PricingPlans\Models\Plan;

interface Subscriber
{
    /**
     * Get a subscription by name.
     *
     * @param  string $name
     * @return PlanSubscription|null
     */
    public function subscription($name = 'default');

    /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription
     * @param  int $planId
     * @return bool
     */
    public function subscribed($subscription = 'default', $planId = null): bool;

    /**
     * Subscribe user to a new plan.
     *
     * @param string $subscription
     * @param \Laravel\PricingPlans\Models\Plan $plan
     * @return \Laravel\PricingPlans\SubscriptionBuilder
     */
    public function newSubscription(string $subscription, Plan $plan);

    /**
     * Get subscription usage manager instance.
     *
     * @param  string $subscription
     * @return \Laravel\PricingPlans\SubscriptionUsageManager
     */
    public function subscriptionUsage($subscription = 'default');
}
