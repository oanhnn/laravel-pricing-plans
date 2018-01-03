<?php

use Faker\Generator;
use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Models\PlanSubscription;
use Laravel\PricingPlans\Models\PlanSubscriptionUsage;

$factory->define(PlanSubscriptionUsage::class, function (Generator $faker) {
    return [
        'subscription_id' => factory(PlanSubscription::class)->create()->id,
        'feature_id' => factory(Feature::class)->create()->id,
        'used' => rand(1, 50),
        'valid_until' => $faker->dateTime(),
    ];
});
