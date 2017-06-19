<?php

namespace Laravel\PricingPlans\Exception;

use Exception;

class FeatureValueFormatIncompatibleException extends Exception
{
    /**
     * Create a new FeatureValueFormatIncompatibleException instance.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->message = "Feature value format is incompatible: {$value}.";
    }
}
