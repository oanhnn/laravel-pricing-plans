<?php

namespace Laravel\PricingPlans\Events;

use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionRenewed
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
    public function getSubscription(): PlanSubscription
    {
        return $this->subscription;
    }
}
