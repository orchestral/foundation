<?php namespace Orchestra\Foundation\Providers;

use Orchestra\Foundation\Meta;
use Orchestra\Foundation\Foundation;
use Orchestra\Support\Providers\ServiceProvider;
use Orchestra\Support\Providers\Traits\AliasesProviderTrait;

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
        'config'                     => 'Orchestra\Config\Repository',
        'auth.driver'                => ['Orchestra\Auth\Guard', 'Orchestra\Contracts\Auth\Guard'],
        'orchestra.platform.acl'     => ['Orchestra\Authorization\Authorization', 'Orchestra\Contracts\Authorization\Authorization'],
        'orchestra.platform.memory'  => ['Orchestra\Memory\Provider', 'Orchestra\Contracts\Memory\Provider'],

        'orchestra.acl'              => ['Orchestra\Authorization\Factory', 'Orchestra\Contracts\Authorization\Factory'],
        'orchestra.app'              => ['Orchestra\Foundation\Foundation', 'Orchestra\Contracts\Foundation\Foundation'],
        'orchestra.asset'            => 'Orchestra\Asset\Factory',
        'orchestra.decorator'        => 'Orchestra\View\Decorator',
        'orchestra.extension.config' => 'Orchestra\Extension\ConfigManager',
        'orchestra.extension.finder' => ['Orchestra\Extension\Finder', 'Orchestra\Contracts\Extension\Finder'],
        'orchestra.extension'        => ['Orchestra\Extension\Factory', 'Orchestra\Contracts\Extension\Factory'],
        'orchestra.form'             => ['Orchestra\Html\Form\Factory', 'Orchestra\Contracts\Html\Form\Factory'],
        'orchestra.mail'             => 'Orchestra\Notifier\Mailer',
        'orchestra.memory'           => 'Orchestra\Memory\MemoryManager',
        'orchestra.messages'         => ['Orchestra\Messages\MessageBag', 'Orchestra\Contracts\Messages\MessageBag'],
        'orchestra.notifier'         => 'Orchestra\Notifier\NotifierManager',
        'orchestra.publisher'        => 'Orchestra\Foundation\Publisher\PublisherManager',
        'orchestra.resources'        => 'Orchestra\Resources\Factory',
        'orchestra.meta'             => 'Orchestra\Foundation\Meta',
        'orchestra.table'            => ['Orchestra\Html\Table\Factory', 'Orchestra\Contracts\Html\Table\Factory'],
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
        'Orchestra\Support\Facades\Foundation' => 'Orchestra\Foundation',
        'Orchestra\Support\Facades\Config' => 'Orchestra\Config',
        'Orchestra\Support\Facades\Extension' => 'Orchestra\Extension',
        'Orchestra\Support\Facades\Form' => 'Orchestra\Form',
        'Orchestra\Support\Facades\Mail' => 'Orchestra\Mail',
        'Orchestra\Support\Facades\Memory' => 'Orchestra\Memory',
        'Orchestra\Support\Facades\Messages' => 'Orchestra\Messages',
        'Orchestra\Support\Facades\Notifier' => 'Orchestra\Notifier',
        'Orchestra\Support\Facades\Publisher' => 'Orchestra\Publisher',
        'Orchestra\Support\Facades\Meta' => 'Orchestra\Meta',
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

        $this->registerFacadesAliases();

        $this->registerCoreContainerAliases();

        $this->registerEvents();
    }

    /**
    * Register the service provider for foundation.
    *
    * @return void
    */
    protected function registerFoundation()
    {
        $this->app['orchestra.installed'] = false;

        $this->app->singleton('orchestra.app', function ($app) {
            return new Foundation($app);
        });
    }

    /**
     * Register the service provider for site.
     *
     * @return void
     */
    protected function registerMeta()
    {
        $this->app->singleton('orchestra.meta', function () {
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
        $path = realpath(__DIR__.'/../../');

        $this->package('orchestra/foundation', 'orchestra/foundation', $path.'/resources');

        if (! $this->app->routesAreCached()) {
            require "{$path}/src/routes.php";
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
