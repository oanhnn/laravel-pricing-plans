<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Positive Words
    |--------------------------------------------------------------------------
    |
    | These words indicates "true" and are used to check if a particular plan
    | feature is enabled.
    |
    */
    'positive_words' => [
        'Y',
        'YES',
        'TRUE',
        'UNLIMITED',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    |
    | If you want to customize name of your tables
    |
    */
    'tables' => [
        'plans'                    => 'plans',
        'plan_features'            => 'plan_features',
        'plan_subscriptions'       => 'plan_subscriptions',
        'plan_subscription_usages' => 'plan_subscription_usages',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want to use your own models you will want to update the following
    | array to make sure this package use them.
    |
    */
    'models' => [
        'Plan'                  => 'Laravel\PricingPlans\Model\Plan',
        'PlanFeature'           => 'Laravel\PricingPlans\Model\PlanFeature',
        'PlanSubscription'      => 'Laravel\PricingPlans\Model\PlanSubscription',
        'PlanSubscriptionUsage' => 'Laravel\PricingPlans\Model\PlanSubscriptionUsage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | The heart of this package. Here you will specify all features available
    | for your plans.
    |
    */
    'features' => [
        'SAMPLE_SIMPLE_FEATURE',
        'SAMPLE_DEFINED_FEATURE' => [
            'reseteable_interval' => 'month',
            'reseteable_count' => 2
        ],
    ],
];
