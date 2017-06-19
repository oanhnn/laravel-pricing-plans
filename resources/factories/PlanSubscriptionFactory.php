<?php

use Laravel\PricingPlans\Model\Plan;
use Laravel\PricingPlans\Model\PlanSubscription;
use Laravel\PricingPlans\Tests\Model\User;

$factory->define(PlanSubscription::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'plan_id' => factory(Plan::class)->create()->id,
        'name' => $faker->word
    ];
});
