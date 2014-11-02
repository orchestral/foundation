<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;
use Orchestra\Support\Traits\AliasesProviderTrait;

class FoundationServiceProvider extends ServiceProvider
{
    use AliasesProviderTrait;

    /**
     * List of core aliases.
     *
     * @var array
     */
    protected $aliases = [
        'app'                        => 'Orchestra\Foundation\Application',
        'orchestra.platform.acl'     => ['Orchestra\Auth\Acl\Acl', 'Orchestra\Contracts\Auth\Acl\Acl'],
        'orchestra.platform.memory'  => ['Orchestra\Memory\Provider', 'Orchestra\Contracts\Memory\Provider'],

        'orchestra.acl'              => 'Orchestra\Auth\Acl\Factory',
        'orchestra.app'              => ['Orchestra\Foundation\Foundation', 'Orchestra\Contracts\Foundation\Foundation'],
        'orchestra.asset'            => 'Orchestra\Asset\Factory',
        'orchestra.decorator'        => 'Orchestra\View\Decorator',
        'orchestra.extension.config' => 'Orchestra\Extension\ConfigManager',
        'orchestra.extension.finder' => 'Orchestra\Extension\Finder',
        'orchestra.extension'        => 'Orchestra\Extension\Factory',
        'orchestra.form'             => 'Orchestra\Html\Form\Factory',
        'orchestra.mail'             => 'Orchestra\Notifier\Mailer',
        'orchestra.memory'           => 'Orchestra\Memory\MemoryManager',
        'orchestra.messages'         => 'Orchestra\Messages\MessageBag',
        'orchestra.notifier'         => 'Orchestra\Notifier\NotifierManager',
        'orchestra.publisher'        => 'Orchestra\Foundation\Publisher\PublisherManager',
        'orchestra.resources'        => 'Orchestra\Resources\Factory',
        'orchestra.meta'             => 'Orchestra\Foundation\Meta',
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
        'Orchestra\Support\Facades\Foundation' => ['Orchestra\Foundation', 'Orchestra\App'],
        'Orchestra\Support\Facades\Config' => 'Orchestra\Config',
        'Orchestra\Support\Facades\Extension' => 'Orchestra\Extension',
        'Orchestra\Support\Facades\Form' => 'Orchestra\Form',
        'Orchestra\Support\Facades\Mail' => 'Orchestra\Mail',
        'Orchestra\Support\Facades\Memory' => 'Orchestra\Memory',
        'Orchestra\Support\Facades\Messages' => 'Orchestra\Messages',
        'Orchestra\Support\Facades\Notifier' => 'Orchestra\Notifier',
        'Orchestra\Support\Facades\Publisher' => 'Orchestra\Publisher',
        'Orchestra\Support\Facades\Resources' => 'Orchestra\Resources',
        'Orchestra\Support\Facades\Meta' => ['Orchestra\Meta', 'Orchestra\Site'],
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
        $this->registerFoundation();

        $this->registerMeta();
    }

    /**
    * Register the service provider for foundation.
    *
    * @return void
    */
    protected function registerFoundation()
    {
        $this->app['orchestra.installed'] = false;

        $this->app->bindShared('orchestra.app', function ($app) {
            return new Foundation($app);
        });

        $this->registerFacadesAliases();
        $this->registerCoreContainerAliases();
        $this->registerEvents();
    }

    /**
     * Register the service provider for site.
     *
     * @return void
     */
    protected function registerMeta()
    {
        $this->app->bindShared('orchestra.meta', function () {
            return new Meta;
        });
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
        return ['orchestra.app', 'orchestra.installed', 'orchestra.meta'];
    }
}
