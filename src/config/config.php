<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | A array list with available locales to load
    |
    */

    'locales' => ['en_US', 'en_US', 'it_IT', 'fr_FR'],

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | A array list with available locales to load
    |
    */

    'default' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Directories to scan
    |--------------------------------------------------------------------------
    |
    | Set directories to scan to find gettext strings (starting with __)
    |
    */

    'directories' => ['app', 'resources'],

    /*
    |--------------------------------------------------------------------------
    | Where the translations are stored
    |--------------------------------------------------------------------------
    |
    | Full path is $storage/xx_XX/LC_MESSAGES/$domain.XX
    |
    */

    'storage' => 'resources/gettext',

    /*
    |--------------------------------------------------------------------------
    | Store files as domain name
    |--------------------------------------------------------------------------
    |
    | Full path is $storage/xx_XX/LC_MESSAGES/$domain.XX
    |
    */

    'domain' => 'messages'
);
