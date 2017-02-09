<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | A array list with available locales to load
    |
    | Default locale will the first in array list
    |
    */

    'locales' => ['en_US', 'es_ES', 'it_IT', 'fr_FR'],

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

    'storage' => 'storage/gettext',

    /*
    |--------------------------------------------------------------------------
    | Store files as domain name
    |--------------------------------------------------------------------------
    |
    | Full path is $storage/xx_XX/LC_MESSAGES/$domain.XX
    |
    */

    'domain' => 'messages',

    /*
    |--------------------------------------------------------------------------
    | Use PHP native gettext functions
    |--------------------------------------------------------------------------
    |
    | Are faster than open files from PHP. If you have enabled the php-gettext
    | module, is recommended to enable.
    |
    */

    'native' => false,

    /*
    |--------------------------------------------------------------------------
    | Use package gettext methods
    |--------------------------------------------------------------------------
    |
    | Enable gettext methods: __, noop__, n__, p__, d__, dp__, np__, dnp__
    |
    | Reference: https://github.com/oscarotero/Gettext/blob/master/src/translator_functions.php
    |
    */

    'functions' => true,

    /*
    |--------------------------------------------------------------------------
    | Preference to load translations from format
    |--------------------------------------------------------------------------
    |
    | Some systems and formats are fatest than others (low RAM or CPU usage)
    | Available options are mo, po, php
    |
    */

    'formats' => ['mo', 'php', 'po'],

    /*
    |--------------------------------------------------------------------------
    | Cookie name
    |--------------------------------------------------------------------------
    |
    | Locale cookie name. Cookie are stored as plain, without Laravel manager
    |
    */

    'cookie' => 'locale'
);
