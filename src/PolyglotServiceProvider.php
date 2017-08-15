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
     * @author glen
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
     * @author glen
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/polyglot.php', 'polyglot');

        Blade::directive('polyglot', function () {
            return "<?php echo app('" . Polyglot::class . "')->generate(); ?>";
        });
    }

}
