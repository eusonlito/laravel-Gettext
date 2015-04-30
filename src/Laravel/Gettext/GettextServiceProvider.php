<?php

namespace Laravel\Gettext;

use Illuminate\Support\ServiceProvider;
use Session;
use Input;

class GettextServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('gettext.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($config = config('gettext')) {
            $this->load($config);
        }

        $this->app['gettext'] = $this->app->share(function($app) {
            return new Gettext;
        });
    }

    public function load(array $config)
    {
        $config['storage'] = base_path($config['storage']);

        foreach ($config['directories'] as $key => $directory) {
            $config['directories'][$key] = base_path($directory);
        }

        Gettext::setConfig($config);

        $_COOKIE['locale'] = isset($_COOKIE['locale']) ? $_COOKIE['locale'] : null;

        Gettext::setConfig($config);

        Gettext::setLocale($_COOKIE['locale'], Input::get('locale'));
        Gettext::load();

        setcookie('locale', $_COOKIE['locale'] = Gettext::getLocale(), 0, url('/'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['gettext'];
    }
}
