<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PolyglotServiceProvider extends ServiceProvider
{
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
            return "<?php echo app('" . PolyglotBladeDirective::class . "')->generate(); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                PolyglotCommand::class,
            ]);
        }
    }

}
