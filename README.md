# Laravel 5.4 Gettext

With this package you can load/parse/store gettext strings

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "eusonlito/laravel-gettext": "2.0.*"
    }
}
```

### Laravel installation

```php

// config/app.php

'providers' => [
    '...',
    'Eusonlito\LaravelGettext\GettextServiceProvider',
];

'aliases' => [
    '...',
    'Gettext'    => 'Eusonlito\LaravelGettext\Facade',
];
```

Now you have a ```Gettext``` facade available.

Publish the config file:

```bash
php artisan vendor:publish
```

### Usage

```php
__('Here your text');
__('Here your text with %s parameters', 1);
__('Here your text with parameters %s and %s', 1, 2);
```

# Gettext Files

By default, gettext .po and .mo files are stored in resources/gettext/xx_XX/LC_MESSAGES/messages.XX

xx_XX is language code like `en_US`, `es_ES`, etc...

# Using your own Gettext function/helper

If you want to create your alternative gettext function:

```php

// config/app.php

'providers' => [
    '...',
    'Eusonlito\LaravelGettext\GettextServiceProvider',
    'App\Providers\GettextServiceProvider',
];
```

Create the file:

```php

// app/Providers/GettextServiceProvider.php

<?php
namespace App\Providers {
    use Illuminate\Support\ServiceProvider;

    class GettextServiceProvider extends ServiceProvider
    {
        public function register()
        {
        }
    }
}

namespace {
    // Change "txt" with your custom gettext function name
    function txt($original)
    {
        $text = app('gettext')->getTranslator()->gettext($original);

        if (func_num_args() === 1) {
            return $text;
        }

        $args = array_slice(func_get_args(), 1);

        return is_array($args[0]) ? strtr($text, $args[0]) : vsprintf($text, $args);
    }
}

```

# Configuration

#### app/config/gettext.php

```php
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
    | Use native gettext functions
    |--------------------------------------------------------------------------
    |
    | Are faster than open files from PHP. If you have enabled the php-gettext
    | module, is recommended to enable.
    |
    */

    'native' => true,

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

    'functions' => false,

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
```
