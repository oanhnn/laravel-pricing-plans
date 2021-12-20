<?php

namespace Laravel\PricingPlans\Events;

use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionCanceled
{
    /**
     * @var PlanSubscription
     */
    protected $subscription;

    /**
     * Create a new event instance.
     *
     * @param PlanSubscription $subscription
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
