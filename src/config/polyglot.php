<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Translation files to include
    |--------------------------------------------------------------------------
    |
    | This value are the translation files that will be loaded to the
    | Polyglot JavaScript variable, see documentation for details.
    */
    'files' => [
        'auth',
        'pagination',
        'passwords',
        'validation'
    ],

    'mode' => 'inline',

    'path' => resource_path('lang'),

    /*
    |--------------------------------------------------------------------------
    | Include Package Translations
    |--------------------------------------------------------------------------
    |
    | Define all packages that will be scanned to collect all translation
    | keys and strings that will be ported to JavaScript via blade or
    | the command approach.
    */
    'packages' => [
        // 
    ]
];
