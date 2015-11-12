<?php
namespace Eusonlito\LaravelGettext;

use Illuminate\Support\ServiceProvider;
use Input;
use App;

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

        if ($config = config('gettext') && isset($config['storage'])) {
            $this->load($config);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('gettext', function($app) {
            return new Gettext;
        });
    }

    public function load(array $config)
    {
        $config['storage'] = base_path($config['storage']);

        foreach ($config['directories'] as $key => $directory) {
            $config['directories'][$key] = base_path($directory);
        }

        $cookie = $config['cookie'];
        $path = parse_url(url('/'), PHP_URL_PATH) ?: '/';

        $_COOKIE[$cookie] = isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : null;

        Gettext::setConfig($config);

        Gettext::setLocale($_COOKIE[$cookie], Input::get($cookie));
        Gettext::load();

        setcookie($cookie, $_COOKIE[$cookie] = Gettext::getLocale(), (time() + 3600 * 24 * 30 * 12), $path);

        App::setLocale(preg_replace('/_.*$/', '', $_COOKIE[$cookie]));
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
