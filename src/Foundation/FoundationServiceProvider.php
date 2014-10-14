<?php namespace Orchestra\Foundation;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * List of core aliases.
     *
     * @var array
     */
    protected $aliases = [
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
    ];

    /**
     * List of core facades.
     *
     * @var array
     */
    protected $facades = [
        'Orchestra\Support\Facades\Asset' => 'Orchestra\Asset',
        'Orchestra\Support\Facades\ACL' => 'Orchestra\ACL',
        'Orchestra\Support\Facades\App' => 'Orchestra\App',
        'Orchestra\Support\Facades\Config' => 'Orchestra\Config',
        'Orchestra\Support\Facades\Extension' => 'Orchestra\Extension',
        'Orchestra\Support\Facades\Form' => 'Orchestra\Form',
        'Orchestra\Support\Facades\Mail' => 'Orchestra\Mail',
        'Orchestra\Support\Facades\Memory' => 'Orchestra\Memory',
        'Orchestra\Support\Facades\Messages' => 'Orchestra\Messages',
        'Orchestra\Support\Facades\Notifier' => 'Orchestra\Notifier',
        'Orchestra\Support\Facades\Profiler' => 'Orchestra\Profiler',
        'Orchestra\Support\Facades\Publisher' => 'Orchestra\Publisher',
        'Orchestra\Support\Facades\Resources' => 'Orchestra\Resources',
        'Orchestra\Support\Facades\Site' => 'Orchestra\Site',
        'Orchestra\Support\Facades\Table' => 'Orchestra\Table',
        'Orchestra\Support\Facades\Theme' => 'Orchestra\Theme',
        'Orchestra\Support\Facades\Widget' => 'Orchestra\Widget',
    ];

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

        $this->registerFacadesAliases();
        $this->registerCoreContainerAliases();
        $this->registerEvents();
    }

    /**
     * Register application aliases.
     *
     * @return void
     */
    protected function registerFacadesAliases()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->facades as $facade => $aliases) {
            foreach ((array) $aliases as $alias) {
                $loader->alias($alias, $facade);
            }
        }
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {
        foreach ($this->aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register additional events for application.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $this->app['router']->after(function () {
            $this->app['events']->fire('orchestra.done');
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
