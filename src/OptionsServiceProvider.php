<?php

namespace Appstract\Options;

use Appstract\Options\Cache\Repository;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
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
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/../config/laravel-options.php' => config_path('laravel-options.php'),
            ]);

            $this->commands([
                \Appstract\Options\Console\OptionSetCommand::class,
            ]);
        }

        $this->app->bind(ObserverConfig::class, function ($app) {
            if (!$app->bound('cache')) {
                $app->singleton('cache', new CacheManager($app));
            }

            return new ObserverConfig([
                'cache'         => new Repository(
                    $app['cache']->driver($app['config']->get('laravel-options.cache.driver'))
                ),
                'valid_minutes' => $app['config']->get('laravel-options.cache.valid_minutes'),
                'except_events' => $this->getRemoveEvents($app['config']->get('laravel-options.events')),
            ]);
        });

        Option::observe($this->app->make(OptionObserver::class));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('option', \Appstract\Options\Option::class);
    }

    /**
     * Remove Unoberve Events
     *
     * @param $events
     * @return array
     */
    protected function getRemoveEvents($events)
    {
        return Collection::make($events)
            ->filter(function ($open) {
                return !$open;
            })
            ->keys()
            ->all();
    }
}
