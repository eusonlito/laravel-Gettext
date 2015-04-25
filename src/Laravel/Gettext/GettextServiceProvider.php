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
        $this->app['gettext'] = $this->app->share(function($app) {
            $config = config('gettext');
            $config['storage'] = base_path(self::$storage);

            Gettext::setConfig($config);
        });

        Gettext::setLocale(Session::get('locale'), Input::get('locale'));
        Gettext::load();

        Session::set('locale', $current);
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
