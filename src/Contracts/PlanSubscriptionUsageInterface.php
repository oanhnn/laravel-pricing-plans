<?php

namespace Laravel\PricingPlans\Contracts;

interface PlanSubscriptionUsageInterface
{
    public function feature();
    public function subscription();
    public function scopeByFeatureCode($query, $featureCode);
    public function isExpired();
}
