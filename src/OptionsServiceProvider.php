<?php

namespace Appstract\Options;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
class OptionsServiceProvider extends ServiceProvider
{
    protected $options;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'migrations');

            $this->commands([
                \Appstract\Options\Console\OptionSetCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $option = Config::get('option.model') ?? \Appstract\Options\Option::class;
        $this->app->bind('option', $option);
    }
}
