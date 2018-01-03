<?php

namespace Laravel\PricingPlans\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionPlanChanged
{
    use SerializesModels;

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
