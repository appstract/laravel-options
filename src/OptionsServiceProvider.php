<?php

namespace Appstract\Options;

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

        collect($this->getDirectives())->each(function ($item, $key) {
            Blade::directive($key, $item);
        });
    }

    /**
     * @return array
     */
    private function getDirectives(): array
    {
        return [

            /*
            |---------------------------------------------------------------------
            | @option, @option_exists, @endoptionexists
            |---------------------------------------------------------------------
            */

            'option' => function ($key, $default_value = null) {
                return "<?php echo option({$key}, $default_value); ?>";
            },

            'optionexists' => function ($key) {
                return "<?php if (option_exists({$key})) : ?>";
            },

            'endoptionexists' => function () {
                return "<?php endif; ?>";
            },
        ];
    }
}
