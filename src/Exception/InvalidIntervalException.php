<?php

namespace Laravel\PricingPlans\Exception;

use Exception;

class InvalidIntervalException extends Exception
{
    /**
     * Create a new InvalidPlanFeatureException instance.
     *
     * @param mixed $interval
     */
    public function __construct($interval)
    {
        $this->message = "Invalid interval \"{$interval}\".";
    }
}
