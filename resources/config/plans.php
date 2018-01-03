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
        'features'                 => 'features',
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
        'Feature'               => 'Laravel\\PricingPlans\\Models\\Feature',
        'Plan'                  => 'Laravel\\PricingPlans\\Models\\Plan',
        'PlanFeature'           => 'Laravel\\PricingPlans\\Models\\PlanFeature',
        'PlanSubscription'      => 'Laravel\\PricingPlans\\Models\\PlanSubscription',
        'PlanSubscriptionUsage' => 'Laravel\\PricingPlans\\Models\\PlanSubscriptionUsage',
    ],

];
