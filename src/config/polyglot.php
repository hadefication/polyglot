<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode (bundle|inline)
    |--------------------------------------------------------------------------
    |
    | Set to "bundle" to omit translations and helper methods in the render of 
    | the @polyglot blade directive. Bundle mode will also require you to 
    | import the generated JavaScript file by the provided command. 
    | Set to "inline" to write everything in the render of the 
    | @polyglot blade directive.
    */
    'mode' => 'inline',

    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    |
    | Limit translation files that will be included by adding their names to 
    | this field. Leave empty you wish to include all language files.
    */
    'files' => [],

    /*
    |--------------------------------------------------------------------------
    | Packages 
    |--------------------------------------------------------------------------
    |
    | Limit packages that will be scanned for language files. Leave empty to 
    | scan all installed packages.
    */
    'packages' => []
];
