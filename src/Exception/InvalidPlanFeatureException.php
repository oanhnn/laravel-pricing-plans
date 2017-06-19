<?php

namespace Laravel\PricingPlans\Exception;

class InvalidPlanFeatureException extends \Exception
{
    /**
     * Create a new InvalidPlanFeatureException instance.
     *
     * @param mixed $feature
     */
    public function __construct($feature)
    {
        $this->message = "Invalid plan feature: {$feature}";
    }
}
