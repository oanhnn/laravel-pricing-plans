<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanSubscription;
use Laravel\PricingPlans\SubscriptionBuilder;
use Laravel\PricingPlans\SubscriptionUsageManager;

trait Subscribable
{
    /**
     * Get user plan subscription.
     *
     * @return MorphMany
     */
    public function subscriptions(): MorphMany
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
     * @return Model|MorphMany|PlanSubscription|object|null
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
     * @param  string|null $planCode
     * @return bool
     */
    public function subscribed(string $subscription, string $planCode = null): bool
    {
        $planSubscription = $this->subscription($subscription);

        if (is_null($planSubscription)) {
            return false;
        }

        if (is_null($planCode) || $planCode == $planSubscription->plan->code) {
            return $subscription->isActive();
        }

        return false;
    }

    /**
     * Subscribe user to a new plan.
     *
     * @param string $subscription Subscription name
     * @param Plan $plan
     * @return SubscriptionBuilder
     */
    public function newSubscription(string $subscription, Plan $plan)
    {
        return new SubscriptionBuilder($this, $subscription, $plan);
    }

    /**
     * Get subscription usage manager instance.
     *
     * @param  string $subscription Subscription name
     * @return SubscriptionUsageManager
     */
    public function subscriptionUsage(string $subscription)
    {
        return new SubscriptionUsageManager($this->subscription($subscription));
    }
}
