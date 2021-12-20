<?php

namespace Laravel\PricingPlans;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanSubscription;

class SubscriptionBuilder
{
    /**
     * The subscriber model that is subscribing.
     *
     * @var Model
     */
    protected $subscriber;

    /**
     * The plan model that the subscriber is subscribing to.
     *
     * @var Plan
     */
    protected $plan;

    /**
     * The subscription name.
     *
     * @var string
     */
    protected $name;

    /**
     * Custom number of trial days to apply to the subscription.
     *
     * This will override the plan trial period.
     *
     * @var int|null
     */
    protected $trialDays;

    /**
     * Do not apply trial to the subscription.
     *
     * @var bool
     */
    protected $skipTrial = false;

    /**
     * Create a new subscription builder instance.
     *
     * @param Model $subscriber
     * @param  string $name  Subscription name
     * @param Plan $plan
     */
    public function __construct(Model $subscriber, string $name, Plan $plan)
    {
        $this->subscriber = $subscriber;
        $this->name = $name;
        $this->plan = $plan;
    }

    /**
     * Specify the trial duration period in days.
     *
     * @param  int $trialDays
     * @return self
     */
    public function trialDays(int $trialDays): SubscriptionBuilder
    {
        $this->trialDays = $trialDays;

        return $this;
    }

    /**
     * Do not apply trial to the subscription.
     *
     * @return self
     */
    public function skipTrial(): SubscriptionBuilder
    {
        $this->skipTrial = true;

        return $this;
    }

    /**
     * Create a new subscription.
     *
     * @param  array  $attributes
     * @return PlanSubscription
     */
    public function create(array $attributes = []): PlanSubscription
    {
        $now = Carbon::now();

        if ($this->skipTrial) {
            $trialEndsAt = null;
        } elseif ($this->trialDays) {
            $trialEndsAt = $now->addDays($this->trialDays);
        } elseif ($this->plan->hasTrial()) {
            $trialEndsAt = $now->addDays($this->plan->trial_period_days);
        } else {
            $trialEndsAt = null;
        }

        return $this->subscriber->subscriptions()->create(array_replace([
            'plan_id' => $this->plan->id,
            'trial_ends_at' => $trialEndsAt,
            'name' => $this->name
        ], $attributes));
    }
}
