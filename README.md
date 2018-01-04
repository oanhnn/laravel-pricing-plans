# Laravel Pricing Plans

[![Build Status](https://travis-ci.org/oanhnn/laravel-pricing-plans.svg?branch=master)](https://travis-ci.org/oanhnn/laravel-pricing-plans)
[![Coverage Status](https://coveralls.io/repos/github/oanhnn/laravel-pricing-plans/badge.svg?branch=master)](https://coveralls.io/github/oanhnn/laravel-pricing-plans?branch=master)
[![Latest Version](https://img.shields.io/github/release/oanhnn/laravel-pricing-plans.svg?style=flat-square)](https://github.com/oanhnn/laravel-pricing-plans/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Easy provide pricing plans for Your Laravel 5.4+ Application.

<!-- MarkdownTOC depth="2" autolink="true" bracket="round" -->

- [Main features](#main-features)
- [TODO](#todo)
- [Requirements](#requirements)
- [Installation](#installation)
    - [Composer](#composer)
    - [Service Provider](#service-provider)
    - [Config file and Migrations](#config-file-and-migrations)
    - [Contract and Traits](#contract-and-traits)
- [Usage](#usage)
    - [Create features and plan](#create-features-and-plan)
    - [Creating subscriptions](#creating-subscriptions)
    - [Subscription Ability](#subscription-ability)
    - [Record Feature Usage](#record-feature-usage)
    - [Reduce Feature Usage](#reduce-feature-usage)
    - [Clear The Subscription Usage Data](#clear-the-subscription-usage-data)
    - [Check Subscription Status](#check-subscription-status)
    - [Renew a Subscription](#renew-a-subscription)
    - [Cancel a Subscription](#cancel-a-subscription)
    - [Scopes](#scopes)
- [Models](#models)
    - [Feature model](#feature-model) 
    - [Plan model](#plan-model) 
    - [PlanFeature model](#planfeature-model) 
    - [PlanSubscription model](#plansubscription-model) 
    - [PlanSubscriptionUsage model](#plansubscriptionusage-model) 
- [Events](#events)
    - [SubscriptionRenewed event](#subscriptionrenewed-event)
    - [SubscriptionCanceled event](#subscriptioncanceled-event)
    - [SubscriptionPlanChanged event](#subscriptionplanchanged-event)
- [Config File](#config-file)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

<!-- /MarkdownTOC -->

## Main features

Easy provide pricing plans for Your Laravel 5.4+ Application.

## TODO

- [ ] Make better document
- [ ] Caching some select query
- [ ] Add unit test scripts

## Requirements

* php >=7.0
* Laravel 5.4+

## Installation

### Composer

Begin by pulling in the package through Composer.

```bash
$ composer require oanhnn/laravel-pricing-plans
```

### Service Provider

Next, if using Laravel 5.5+, you done. If using Laravel 5.4, you must include the service provider within your `config/app.php` file.

```php
// config/app.php

    'providers' => [
        // Other service providers...

        Laravel\PricingPlans\PricingPlansServiceProvider::class,
    ],
```

### Config file and Migrations

Publish package config file and migrations with the command:

```bash
$ php artisan vendor:publish --provider="Laravel\PricingPlans\PricingPlansServiceProvider"
```

Then run migrations:

```bash
    php artisan migrate
```

### Contract and Traits

Add `Laravel\PricingPlans\Contacts\Subscriber` contract and `Laravel\PricingPlans\Models\Concerns\Subscribable` trait 
to your subscriber model (Eg. `User`).

See the following example:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\PricingPlans\Contracts\Subscriber;
use Laravel\PricingPlans\Models\Concerns\Subscribable;

class User extends Authenticatable implements Subscriber
{
    use Subscribable;
    // ...
}
```

## Usage

### Create features and plan

```php
<?php

use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Models\Plan;

$feature1 = Feature::create([
    'name' => 'upload images',
    'description' => null,
    'interval_unit' => 'day',
    'interval_count' => 1,
    'sort_order' => 1,
]);

$feature2 = Feature::create([
    'name' => 'upload video',
    'description' => null,
    'interval_unit' => 'day',
    'interval_count' => 1,
    'sort_order' => 1,
]);

$plan = Plan::create([
    'name' => 'Pro',
    'description' => 'Pro plan',
    'price' => 9.99,
    'interval_unit' => 'month',
    'interval_count' => 1,
    'trial_period_days' => 5,
    'sort_order' => 1,
]);

$plan->features()->attach([
    $feature1->id => ['value' => 5, 'note' => 'Can upload maximum 5 images daily'],
    $feature2->id => ['value' => 1, 'note' => 'Can upload maximum 1 video daily'],
]);

define('LPP_FEATURE_UPLOAD_IMAGES', $feature1->id);
define('LPP_FEATURE_UPLOAD_VIDEO', $feature2->id);
define('LPP_PLAN_PRO', $plan->id);
```

### Creating subscriptions

You can subscribe a user to a plan by using the `newSubscription()` function available in the `Subscribable` trait. 
First, retrieve an instance of your subscriber model, which typically will be your user model and an instance of the plan
your user is subscribing to. Once you have retrieved the model instance, you may use the `newSubscription` method 
to create the model's subscription.

```php
<?php

use Illuminate\Support\Facades\Auth;
use Laravel\PricingPlans\Models\Plan;

$user = Auth::user();
$plan = Plan::find(LPP_PLAN_PRO);

$user->newSubscription('main', $plan)->create();
```

The first argument passed to `newSubscription` method should be the name of the subscription. If your application offer 
a single subscription, you might call this `main` or `primary`. The second argument is the plan instance your user is subscribing to.

<!-- ~~If both plans (current and new plan) have the same billing frequency (e.g., ` interval_unit` and `interval_count`) the subscription 
will retain the same billing dates. If the plans don't have the same billing frequency, the subscription will have the new plan billing frequency, 
starting on the day of the change and _the subscription usage data will be cleared_.~~ -->

<!-- ~~If the new plan have a trial period and it's a new subscription, the trial period will be applied.~~ -->

### Subscription Ability

There's multiple ways to determine the usage and ability of a particular feature in the user subscription, the most common one is `canUse`:

The `canUse` method returns `true` or `false` depending on multiple factors:

- Feature _is enabled_.
- Feature value isn't `0`.
- Or feature has remaining uses available.

```php
$user->subscription('main')->ability()->canUse(LPP_FEATURE_UPLOAD_IMAGES);
```

Other methods are:

- `enabled`: returns `true` when the value of the feature is a _positive word_ listed in the config file.
- `consumed`: returns how many times the user has used a particular feature.
- `remainings`: returns available uses for a particular feature.
- `value`: returns the feature value.

> All methods share the same signature: e.g. 
>`$user->subscription('main')->ability()->consumed(LPP_FEATURE_UPLOAD_IMAGES);`.


### Record Feature Usage

In order to efectively use the ability methods you will need to keep track of every usage of each feature 
(or at least those that require it). You may use the `record` method available through the user `subscriptionUsage()` 
method:

```php
$user->subscriptionUsage('main')->record(LPP_FEATURE_UPLOAD_IMAGES);
```

The `record` method accept 3 parameters: the first one is the feature's code, the second one is the quantity of 
uses to add (default is `1`), and the third one indicates if the addition should be incremental (default behavior), 
when disabled the usage will be override by the quantity provided. E.g.:

```php
// Increment by 2
$user->subscriptionUsage('main')->record(LPP_FEATURE_UPLOAD_IMAGES, 2);

// Override with 9
$user->subscriptionUsage('main')->record(LPP_FEATURE_UPLOAD_IMAGES, 9, false);
```

### Reduce Feature Usage

Reducing the feature usage is _almost_ the same as incrementing it. Here we only _substract_ a given quantity (default is `1`) 
to the actual usage:

```php
$user->subscriptionUsage('main')->reduce(LPP_FEATURE_UPLOAD_IMAGES, 2);
```

### Clear The Subscription Usage Data

```php
$user->subscriptionUsage('main')->clear();
```

### Check Subscription Status

For a subscription to be considered active _one of the following must be `true`_:

- Subscription has an active trial.
- Subscription `ends_at` is in the future.

```php
$user->subscribed('main');
$user->subscribed('main', $planId); // Check if user is using a particular plan
```

Alternatively you can use the following methods available in the subscription model:

```php
$user->subscription('main')->isActive();
$user->subscription('main')->isCanceled();
$user->subscription('main')->isCanceledImmediately();
$user->subscription('main')->isEnded();
$user->subscription('main')->onTrial();
```

> Canceled subscriptions with an active trial or `ends_at` in the future are considered active.

### Renew a Subscription

To renew a subscription you may use the `renew` method available in the subscription model. 
This will set a new `ends_at` date based on the selected plan and _will clear the usage data_ of the subscription.

```php
$user->subscription('main')->renew();
```

_Canceled subscriptions with an ended period can't be renewed._

### Cancel a Subscription

To cancel a subscription, simply use the `cancel` method on the user's subscription:

```php
$user->subscription('main')->cancel();
```

By default the subscription will remain active until the end of the period, you may pass `true` to end the subscription _immediately_:

```php
$user->subscription('main')->cancel(true);
```

### Scopes

#### Subscription Model
```php
<?php

use Laravel\PricingPlans\Models\PlanSubscription;

// Get subscriptions by plan:
$subscriptions = PlanSubscription::byPlan($plan_id)->get();

// Get subscription by subscriber:
$subscription = PlanSubscription::bySubscriber($user)->first();

// Get subscriptions with trial ending in 3 days:
$subscriptions = PlanSubscription::findEndingTrial(3)->get();

// Get subscriptions with ended trial:
$subscriptions = PlanSubscription::findEndedTrial()->get();

// Get subscriptions with period ending in 3 days:
$subscriptions = PlanSubscription::findEndingPeriod(3)->get();

// Get subscriptions with ended period:
$subscriptions = PlanSubscription::findEndedPeriod()->get();
```

## Models

PricingPlans uses 5 models under namespace `Laravel\PricingPlans\Models`. You can change to using extended classes of it by 
changing models class in config file:

### Feature model

This model is model object of feature

```php
<?php

namespace App\Models;

use Laravel\PricingPlans\Models\Feature as Model;

class Feature extends Model
{
    const FEATURE_UPLOAD_IMAGES = 1;
    const FEATURE_UPLOAD_VIDEO = 2;
}
```

### Plan model

This model is model object of plan

```php
<?php
namespace App\Models;

use Laravel\PricingPlans\Models\Plan as Model;

class Plan extends Model
{
    const PLAN_FREE = 1;
    const PLAN_PRO = 2;
}
```

### PlanFeature model

This model is relation model object between plan and feature

### PlanSubscription model

This model is relation model object between plan and subscriber

### PlanSubscriptionUsage model

This model is object for counting usage feature

For more details take a look to each model and the `Laravel\PricingPlans\Models\Concerns\Subscribable` trait.

## Events

Events are under the namespace `Laravel\PricingPlans\Events`. The following are the events triggered by the package.

### `SubscriptionRenewed` event

Fired when a subscription is renewed using the `renew()` method.

### `SubscriptionCanceled` event

Fired when a subscription is canceled using the `cancel()` method.

### `SubscriptionPlanChanged` event

Fired when a subscription's plan is changed. This will be triggered once the `PlanSubscription` model is saved. 
Plan change is determine by comparing the original and current value of `plan_id`.

## Config File

You can configure what database tables, what models to use, list of positive words will use.

Definitions:

- **Positive Words**: Are used to tell if a particular feature is _enabled_. E.g., if the feature `listing_title_bold` 
   has the value `Y` (_Y_ is one of the positive words) then, that means it's enabled.

Take a look to the `config/plans.php` config file for more details.

## Changelog

See all change logs in [CHANGELOG](CHANGELOG.md)

## Testing

```bash
$ git clone git@github.com/oanhnn/laravel-pricing-plans.git /path
$ cd /path
$ composer install
$ composer phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email to [Oanh Nguyen](mailto:oanhnn.bk@gmail.com) instead of 
using the issue tracker.

## Credits

- [Oanh Nguyen](https://github.com/oanhnn)
- [All Contributors](../../contributors)

## License

This project is released under the MIT License.   
Copyright © 2017-2018 [Oanh Nguyen](https://oanhnn.github.io/).
