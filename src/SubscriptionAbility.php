<?php

namespace Laravel\PricingPlans;

use Illuminate\Support\Facades\Config;
use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionAbility
{
    /**
     * Subscription model instance.
     *
     * @var \Laravel\PricingPlans\Models\PlanSubscription
     */
    protected $subscription;

    /**
     * Create a new Subscription instance.
     *
     * @param \Laravel\PricingPlans\Models\PlanSubscription $subscription
     */
    public function __construct(PlanSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Determine if the feature is enabled and has
     * available uses.
     *
     * @param int $featureId
     * @return bool
     */
    public function canUse($featureId): bool
    {
        // Get features and usage
        $featureValue = $this->value($featureId);

        if (is_null($featureValue)) {
            return false;
        }

        // Match "boolean" type value
        if ($this->enabled($featureId) === true) {
            return true;
        }

        // If the feature value is zero, let's return false
        // since there's no uses available. (useful to disable
        // countable features)
        if ($featureValue === '0') {
            return false;
        }

        // Check for available uses
        return $this->remainings($featureId) > 0;
    }

    /**
     * Get how many times the feature has been used.
     *
     * @param int $featureId
     * @return int
     */
    public function consumed($featureId)
    {
        /** @var \Laravel\PricingPlans\Models\PlanSubscriptionUsage $usage */
        foreach ($this->subscription->usage as $usage) {
            if ($usage->feature_id === $featureId && $usage->isExpired()) {
                return $usage->used;
            }
        }

        return 0;
    }

    /**
     * Get the available uses.
     *
     * @param int $featureId
     * @return int
     */
    public function remainings($featureId)
    {
        return ($this->value($featureId) - $this->consumed($featureId));
    }

    /**
     * Check if subscription plan feature is enabled.
     *
     * @param int $featureId
     * @return bool
     */
    public function enabled($featureId)
    {
        $featureValue = $this->value($featureId);

        if (is_null($featureValue)) {
            return false;
        }

        // If value is one of the positive words configured then the
        // feature is enabled.
        if (in_array(strtoupper($featureValue), Config::get('plans.positive_words'))) {
            return true;
        }

        return false;
    }

    /**
     * Get feature value.
     *
     * @param int $featureId
     * @param  mixed $default
     * @return mixed
     */
    public function value($featureId, $default = null)
    {
        /** @var \Laravel\PricingPlans\Models\Feature $feature */
        foreach ($this->subscription->plan->features as $feature) {
            if ($featureId === $feature->id) {
                return $feature->pivot->value;
            }
        }

        return $default;
    }
}
