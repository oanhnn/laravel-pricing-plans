<?php

namespace Laravel\PricingPlans;

use Illuminate\Support\ServiceProvider;
use Laravel\PricingPlans\Contracts\PlanInterface;
use Laravel\PricingPlans\Contracts\PlanFeatureInterface;
use Laravel\PricingPlans\Contracts\PlanSubscriptionInterface;
use Laravel\PricingPlans\Contracts\PlanSubscriptionUsageInterface;

/**
 * Class PricingPlansServiceProvider
 *
 * @package Laravel\PricingPlans
 */
class PricingPlansServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $pkg = __DIR__ . '/../resources';

        $this->loadTranslationsFrom($pkg . '/lang', 'pricing_plans');

        $this->publishes([
            $pkg . '/migrations/create_plans_table.php'
            => database_path('migrations/' . date('Y_m_d_His') . 'create_plans_table.php')
        ], 'migrations');

        $this->publishes([
            $pkg . '/config/plans.php' => config_path('plans.php')
        ], 'config');

        $this->publishes([
            $pkg . '/lang' => resource_path('lang/vendor/pricing_plans'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/plans.php', 'plans');

        $this->app->bind(PlanInterface::class, config('plans.models.Plan'));
        $this->app->bind(PlanFeatureInterface::class, config('plans.models.PlanFeature'));
        $this->app->bind(PlanSubscriptionInterface::class, config('plans.models.PlanSubscription'));
        $this->app->bind(PlanSubscriptionUsageInterface::class, config('plans.models.PlanSubscriptionUsage'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PlanInterface::class,
            PlanFeatureInterface::class,
            PlanSubscriptionInterface::class,
            PlanSubscriptionUsageInterface::class,
        ];
    }
}
