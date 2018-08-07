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
     * @param string $featureCode
     * @return bool
     */
    public function canUse(string $featureCode): bool
    {
        // Get features and usage
        $featureValue = $this->value($featureCode);

        if (is_null($featureValue)) {
            return false;
        }

        // Match "boolean" type value
        if ($this->enabled($featureCode) === true) {
            return true;
        }

        // If the feature value is zero, let's return false
        // since there's no uses available. (useful to disable
        // countable features)
        if ($featureValue === '0') {
            return false;
        }

        // Check for available uses
        return $this->remainings($featureCode) > 0;
    }

    /**
     * Get how many times the feature has been used.
     *
     * @param string $featureCode
     * @return int
     */
    public function consumed(string $featureCode): int
    {
        /** @var \Laravel\PricingPlans\Models\PlanSubscriptionUsage $usage */
        foreach ($this->subscription->usage as $usage) {
            if ($usage->feature_code === $featureCode && !$usage->isExpired()) {
                return (int) $usage->used;
            }
        }

        return 0;
    }

    /**
     * Get the available uses.
     *
     * @param string $featureCode
     * @return int
     */
    public function remainings(string $featureCode): int
    {
        return (int)$this->value($featureCode) - $this->consumed($featureCode);
    }

    /**
     * Check if subscription plan feature is enabled.
     *
     * @param string $featureCode
     * @return bool
     */
    public function enabled(string $featureCode): bool
    {
        $featureValue = $this->value($featureCode);

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
     * @param string$featureCode
     * @param  mixed $default
     * @return mixed
     */
    public function value(string $featureCode, $default = null)
    {
        if (!$this->subscription->plan->relationLoaded('features')) {
            $this->subscription->plan->features()->getEager();
        }

        /** @var \Laravel\PricingPlans\Models\Feature $feature */
        foreach ($this->subscription->plan->features as $feature) {
            if ($featureCode === $feature->code) {
                return $feature->pivot->value;
            }
        }

        return $default;
    }
}
