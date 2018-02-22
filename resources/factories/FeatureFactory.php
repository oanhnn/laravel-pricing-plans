<?php

use Faker\Generator;
use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Period;

$factory->define(Feature::class, function (Generator $faker) {
    return [
        'name' => $faker->word,
        'code' => $faker->unique()->slug,
        'description' => $faker->sentence,
        'interval_unit' => $faker->randomElement([null, Period::DAY, Period::WEEK, Period::MONTH, Period::YEAR]),
        'interval_count' => $faker->numberBetween(0, 2),
    ];
});
