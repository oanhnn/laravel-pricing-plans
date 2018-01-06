<?php

namespace Laravel\PricingPlans\Tests;

use Faker\Generator as FakerGenerator;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Laravel\PricingPlans\PricingPlansServiceProvider;
use Laravel\PricingPlans\Tests\Models\User;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\TestCase as Testbench;

class TestCase extends Testbench
{
    /**
     * Setup the test environment.
     *
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();

        // Run Laravel migrations
        $this->loadLaravelMigrations('testbench');

        // Run package migrations
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../resources/migrations'),
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->registerEloquentFactory($app);
        // Set user model
        $app['config']->set('auth.providers.users.model', User::class);
        // set up database configuration
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Get Laraplans package service provider.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            ConsoleServiceProvider::class,
            PricingPlansServiceProvider::class,
        ];
    }

    /**
     * Register the Eloquent factory instance in the container.
     *
     * @return void
     */
    protected function registerEloquentFactory($app)
    {
        $app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create();
        });
        $app->singleton(EloquentFactory::class, function ($app) {
            $faker = $app->make(FakerGenerator::class);
            return EloquentFactory::construct($faker, __DIR__ . '/../resources/factories');
        });
    }
}
