<?php

namespace Laravel\PricingPlans;

use Illuminate\Support\ServiceProvider;

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

        $this->loadTranslationsFrom($pkg . '/lang', 'plans');

        $this->publishes([
            $pkg . '/migrations/2018_01_01_000000_create_plans_tables.php'
            => database_path('migrations/' . date('Y_m_d_His') . '_create_plans_tables.php')
        ], 'migrations');

        $this->publishes([
            $pkg . '/config/plans.php' => config_path('plans.php')
        ], 'config');

        $this->publishes([
            $pkg . '/lang' => resource_path('lang/vendor/plans'),
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
    }
}
