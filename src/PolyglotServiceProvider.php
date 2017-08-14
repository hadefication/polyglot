<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\ServiceProvider;

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
        
    }

}
