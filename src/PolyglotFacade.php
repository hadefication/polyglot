<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Facades\Facade;

class PolyglotFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'polyglot';
    }

}
