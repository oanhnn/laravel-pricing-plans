<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Illuminate\Support\Facades\Config;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\SubscriptionBuilder;
use Laravel\PricingPlans\SubscriptionUsageManager;

trait Subscribable
{
    /**
     * Get user plan subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(
            Config::get('plans.models.PlanSubscription'),
            'subscriber'
        );
    }

    /**
     * Get a subscription by name.
     *
     * @param  string $name Subscription name
     * @return \Laravel\PricingPlans\Models\PlanSubscription|null
     */
    public function subscription(string $name = 'default')
    {
        if ($this->relationLoaded('subscriptions')) {
            return $this->subscriptions
                ->orderByDesc(function ($subscription) {
                    return $subscription->created_at->getTimestamp();
                })
                ->first(function ($subscription) use ($name) {
                    return $subscription->name === $name;
                });
        }

        return $this->subscriptions()
            ->where('name', $name)
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription Subscription name
     * @param  int|null $planId
     * @return bool
     */
    public function subscribed(string $subscription, $planId = null): bool
    {
        $subscription = $this->subscription($subscription);

        if (is_null($subscription)) {
            return false;
        }

        if (is_null($planId) || $planId == $subscription->plan_id) {
            return $subscription->isActive();
        }

        return false;
    }

    /**
     * Subscribe user to a new plan.
     *
     * @param string $subscription Subscription name
     * @param \Laravel\PricingPlans\Models\Plan $plan
     * @return \Laravel\PricingPlans\SubscriptionBuilder
     */
    public function newSubscription(string $subscription, Plan $plan)
    {
        return new SubscriptionBuilder($this, $subscription, $plan);
    }

    /**
     * Get subscription usage manager instance.
     *
     * @param  string $subscription Subscription name
     * @return \Laravel\PricingPlans\SubscriptionUsageManager
     */
    public function subscriptionUsage($subscription)
    {
        return new SubscriptionUsageManager($this->subscription($subscription));
    }
}
