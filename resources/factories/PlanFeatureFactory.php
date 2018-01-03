<?php

use Faker\Generator;
use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanFeature;

$factory->define(PlanFeature::class, function (Generator $faker) {
    return [
        'plan_id' => factory(Plan::class)->create()->id,
        'feature_id' => factory(Feature::class)->create()->id,
        'value' => $faker->randomElement(['10','20','30','50','Y','N','UNLIMITED', null]),
    ];
});
