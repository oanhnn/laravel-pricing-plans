<?php

use Faker\Generator;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Models\PlanSubscription;
use Laravel\PricingPlans\Tests\Models\User;

$factory->define(PlanSubscription::class, function (Generator $faker) {
    return [
        'subscriber_type' => User::class,
        'subscriber_id' => factory(User::class)->create()->id,
        'plan_id' => factory(Plan::class)->create()->id,
        'name' => $faker->word,
        'canceled_immediately' => false,
    ];
});
