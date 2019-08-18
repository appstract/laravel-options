<?php

namespace Appstract\Options\Test;

use Orchestra\Testbench\TestCase;
use Appstract\Options\OptionFacade;
use Appstract\Options\OptionsServiceProvider;

abstract class BaseTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('laravel-options.cache', [
            'driver'        => 'array',
            'valid_minutes' => 1,
        ]);
        $app['config']->set('laravel-options.events', [
            'created' => true,
            'updated' => true,
            'deleted' => true,
            'finding' => true,
            'found'   => true,
            'exists'  => true,
        ]);

        $app['config']->set(
            'database.connections.testbench', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]
        );

        $app['config']->set(
            'cache.stores.file', [
                'driver' => 'array',
            ]
        );
    }

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            OptionsServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Option' => OptionFacade::class,
        ];
    }
}
