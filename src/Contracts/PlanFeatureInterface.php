<?php

namespace Laravel\PricingPlans\Contracts;

interface PlanFeatureInterface
{
    public function plan();
    public function usage();
}
