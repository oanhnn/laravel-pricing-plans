<?php

namespace Laravel\PricingPlans\Events;

use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionPlanChanged
{
    /**
     * @var PlanSubscription
     */
    protected $subscription;

    /**
     * Create a new event instance.
     *
     * @param  \Laravel\PricingPlans\Models\PlanSubscription $subscription
     */
    public function __construct(PlanSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return PlanSubscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
}
