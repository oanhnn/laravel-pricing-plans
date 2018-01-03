<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Illuminate\Support\Facades\Config;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanSubscription;
use Laravel\PricingPlans\SubscriptionBuilder;
use Laravel\PricingPlans\SubscriptionUsageManager;

trait Subscribable
{
    /**
     * Get a subscription by name.
     *
     * @param  string $name
     * @return PlanSubscription|null
     */
    public function subscription($name = 'default')
    {
        return $this->subscriptions
            ->sortByDesc(function ($value) {
                return $value->created_at->getTimestamp();
            })
            ->first(function ($subscription) use ($name) {
                return $subscription->name === $name;
            });
    }

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
     * Check if the user has a given subscription.
     *
     * @param  string $subscription
     * @param  int $planId
     * @return bool
     */
    public function subscribed($subscription = 'default', $planId = null): bool
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
     * @param string $subscription
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
     * @param  string $subscription
     * @return \Laravel\PricingPlans\SubscriptionUsageManager
     */
    public function subscriptionUsage($subscription = 'default')
    {
        return new SubscriptionUsageManager($this->subscription($subscription));
    }
}
