<?php namespace Orchestra\Foundation;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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
        $this->registerCoreContainerAliases();
        $this->registerEvents();
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
        $loader->alias('Orchestra\Acl', 'Orchestra\Support\Facades\ACL');
        $loader->alias('Orchestra\ACL', 'Orchestra\Support\Facades\ACL');
        $loader->alias('Orchestra\App', 'Orchestra\Support\Facades\App');
        $loader->alias('Orchestra\Config', 'Orchestra\Support\Facades\Config');
        $loader->alias('Orchestra\Extension', 'Orchestra\Support\Facades\Extension');
        $loader->alias('Orchestra\Form', 'Orchestra\Support\Facades\Form');
        $loader->alias('Orchestra\Mail', 'Orchestra\Support\Facades\Mail');
        $loader->alias('Orchestra\Memory', 'Orchestra\Support\Facades\Memory');
        $loader->alias('Orchestra\Messages', 'Orchestra\Messages\Facade');
        $loader->alias('Orchestra\Notifier', 'Orchestra\Support\Facades\Notifier');
        $loader->alias('Orchestra\Profiler', 'Orchestra\Support\Facades\Profiler');
        $loader->alias('Orchestra\Publisher', 'Orchestra\Support\Facades\Publisher');
        $loader->alias('Orchestra\Resources', 'Orchestra\Support\Facades\Resources');
        $loader->alias('Orchestra\Site', 'Orchestra\Support\Facades\Site');
        $loader->alias('Orchestra\Table', 'Orchestra\Support\Facades\Table');
        $loader->alias('Orchestra\Theme', 'Orchestra\Support\Facades\Theme');
        $loader->alias('Orchestra\Widget', 'Orchestra\Support\Facades\Widget');
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {
        $aliases = array(
            'orchestra.acl'              => 'Orchestra\Auth\Acl\Factory',
            'orchestra.app'              => 'Orchestra\Foundation\Application',
            'orchestra.asset'            => 'Orchestra\Asset\Factory',
            'orchestra.decorator'        => 'Orchestra\View\Decorator',
            'orchestra.extension.config' => 'Orchestra\Extension\ConfigManager',
            'orchestra.extension.finder' => 'Orchestra\Extension\Finder',
            'orchestra.extension'        => 'Orchestra\Extension\Factory',
            'orchestra.facile'           => 'Orchestra\Facile\Factory',
            'orchestra.form'             => 'Orchestra\Html\Form\Factory',
            'orchestra.mail'             => 'Orchestra\Notifier\Mailer',
            'orchestra.memory'           => 'Orchestra\Memory\MemoryManager',
            'orchestra.messages'         => 'Orchestra\Messages\MessageBag',
            'orchestra.notifier'         => 'Orchestra\Notifier\NotifierManager',
            'orchestra.profiler'         => 'Orchestra\Debug\Profiler',
            'orchestra.publisher'        => 'Orchestra\Foundation\Publisher\PublisherManager',
            'orchestra.resources'        => 'Orchestra\Resources\Factory',
            'orchestra.site'             => 'Orchestra\Foundation\Site',
            'orchestra.table'            => 'Orchestra\Html\Table\Factory',
            'orchestra.theme'            => 'Orchestra\View\Theme\ThemeManager',
            'orchestra.widget'           => 'Orchestra\Widget\WidgetManager',
        );

        foreach ($aliases as $key => $alias) {
            $this->app->alias($key, $alias);
        }
    }

    /**
     * Register additional events for application.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $app = $this->app;

        $app['router']->after(function () use ($app) {
            $app['events']->fire('orchestra.done');
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../');

        $this->package('orchestra/foundation', 'orchestra/foundation', $path);

        $this->app['orchestra.app']->boot();

        require "{$path}/start/global.php";
        require "{$path}/start/macros.php";
        require "{$path}/start/events.php";

        if (! $this->app->routesAreCached()) {
            require "{$path}/routes.php";
        }

        $this->app['events']->fire('orchestra.ready');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['orchestra.app', 'orchestra.installed'];
    }
}
