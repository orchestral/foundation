<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['orchestra.installed'] = false;

        $this->app->bindShared('orchestra.app', function ($app) {
            return new Application($app);
        });

        $this->registerAliases();
    }

    /**
     * Register application aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Orchestra\Asset', 'Orchestra\Support\Facades\Asset');
        $loader->alias('Orchestra\Acl', 'Orchestra\Support\Facades\Acl');
        $loader->alias('Orchestra\App', 'Orchestra\Support\Facades\App');
        $loader->alias('Orchestra\Config', 'Orchestra\Support\Facades\Config');
        $loader->alias('Orchestra\Extension', 'Orchestra\Support\Facades\Extension');
        $loader->alias('Orchestra\Form', 'Orchestra\Support\Facades\Form');
        $loader->alias('Orchestra\Mail', 'Orchestra\Support\Facades\Mail');
        $loader->alias('Orchestra\Memory', 'Orchestra\Support\Facades\Memory');
        $loader->alias('Orchestra\Messages', 'Orchestra\Support\Facades\Messages');
        $loader->alias('Orchestra\Profiler', 'Orchestra\Debug\Facades\Profiler');
        $loader->alias('Orchestra\Publisher', 'Orchestra\Support\Facades\Publisher');
        $loader->alias('Orchestra\Resources', 'Orchestra\Support\Facades\Resources');
        $loader->alias('Orchestra\Site', 'Orchestra\Support\Facades\Site');
        $loader->alias('Orchestra\Table', 'Orchestra\Support\Facades\Table');
        $loader->alias('Orchestra\Theme', 'Orchestra\Support\Facades\Theme');
        $loader->alias('Orchestra\Widget', 'Orchestra\Support\Facades\Widget');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../');

        $this->package('orchestra/foundation', 'orchestra/foundation', $path);

        $this->app['orchestra.app']->boot();

        include "{$path}/start.php";

        $this->app['events']->fire('orchestra.ready');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('orchestra.app', 'orchestra.installed');
    }
}
