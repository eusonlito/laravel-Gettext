<?php
namespace Eusonlito\LaravelGettext;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

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
        return $this->load(config('gettext'));
    }

    public function load(array $config)
    {
	if (empty($config)) {
		return;
	}

        $config['storage'] = base_path($config['storage']);

        foreach ($config['directories'] as $key => $directory) {
            $config['directories'][$key] = base_path($directory);
        }

        $cookie = $config['cookie'];
        $path = parse_url(url('/'), PHP_URL_PATH) ?: '/';

        $_COOKIE[$cookie] = isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : null;

        $gettext = new Gettext($config);

        $gettext->setLocale($_COOKIE[$cookie], Input::get($cookie));
        $gettext->load();

        setcookie($cookie, $_COOKIE[$cookie] = $gettext->getLocale(), (time() + 3600 * 24 * 30 * 12), $path);

        $this->app->singleton('gettext', function() use ($gettext) {
            return $gettext;
        });

        return $gettext;
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
