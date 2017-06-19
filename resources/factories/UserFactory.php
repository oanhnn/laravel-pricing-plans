<?php

use Illuminate\Support\Facades\Hash;
use Laravel\PricingPlans\Tests\Model\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => Hash::make(str_random(10)),
        'remember_token' => str_random(10),
    ];
});
