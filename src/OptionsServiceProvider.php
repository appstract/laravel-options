<?php

namespace Appstract\Options;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
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
        $this->app->bind('option', \Appstract\Options\Option::class);

        $this->directives()->each(function ($item, $key) {
            Blade::directive($key, $item);
        });
    }

    private function directives(): Collection
    {
        return collect([
            'option' => function ($key, $default = null) {
                return "<?php echo option({$key}, {$default}); ?>";
            },

            'optionExists' => function ($key) {
                return "<?php if (option_exists({$key})) : ?>";
            },
        ]);
    }
}
