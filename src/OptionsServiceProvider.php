<?php

namespace Appstract\Options;

use Illuminate\Support\ServiceProvider;

class OptionsServiceProvider extends ServiceProvider
{
    protected $options;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../database/migrations' => database_path('migrations'),
                ], 'migrations'
            );

            $this->commands(
                [
                    Console\OptionSetCommand::class,
                ]
            );
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('option', Option::class);
    }
}
