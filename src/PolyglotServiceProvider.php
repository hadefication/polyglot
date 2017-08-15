<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class PolyglotServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('polyglot', function () {
            return $this->app->make('Hadefication\Polyglot\Polyglot');
        });
    }

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/polyglot.php', 'polyglot');

        $this->publishes([
            __DIR__.'/config/polyglot.php' => config_path('polyglot.php')
        ], 'config');

        Blade::directive('polyglot', function () {
            return "<?php echo app('" . Polyglot::class . "')->generate(); ?>";
        });
    }

}
