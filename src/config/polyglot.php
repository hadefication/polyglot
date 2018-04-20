<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Setup
    |--------------------------------------------------------------------------
    |
    | Here you may configure the setup if you want it as default where you only
    | add the @polyglot directive in your master blade file or opt for the 
    | advance setup where you can include a JavaScript file to your
    | build pipeline. See the documentations for more details.

    | Available Setups: "default", "advance"
    |
    */
    'setup' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    |
    | The location of your app's translation files. By default, it is located
    | in ./resources/lang. Modify this if you have a custom location for
    | your translation files.
    |
    */
    'location' => base_path('resources/lang')
];
