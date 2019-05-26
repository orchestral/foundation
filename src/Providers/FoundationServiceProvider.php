<?php

namespace Orchestra\Foundation\Providers;

use Laravie\Authen\Authen;
use Orchestra\Foundation\Meta;
use Orchestra\Foundation\Foundation;
use Laravie\Authen\BootAuthenProvider;
use Orchestra\Foundation\RouteResolver;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Support\Providers\ServiceProvider;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins;
use Orchestra\Support\Providers\Concerns\AliasesProvider;
use Orchestra\Foundation\Auth\Throttle\Basic as BasicThrottle;

class FoundationServiceProvider extends ServiceProvider
{
    use AliasesProvider, BootAuthenProvider;

    /**
     * List of core aliases.
     *
     * @var array
     */
    protected $aliases = [
        'app' => ['Orchestra\Foundation\Application'],
        'config' => ['Orchestra\Config\Repository'],
        'auth.driver' => ['Orchestra\Auth\SessionGuard', 'Orchestra\Contracts\Auth\Guard'],
        'orchestra.platform.acl' => ['Orchestra\Authorization\Authorization', 'Orchestra\Contracts\Authorization\Authorization'],
        'orchestra.platform.memory' => ['Orchestra\Memory\Provider', 'Orchestra\Contracts\Memory\Provider'],

        'orchestra.acl' => ['Orchestra\Authorization\Factory', 'Orchestra\Contracts\Authorization\Factory'],
        'orchestra.app' => ['Orchestra\Foundation\Foundation', 'Orchestra\Contracts\Foundation\Foundation'],
        'orchestra.asset' => ['Orchestra\Asset\Factory'],
        'orchestra.decorator' => ['Orchestra\View\Decorator'],
        'orchestra.extension.config' => ['Orchestra\Extension\ConfigManager'],
        'orchestra.extension.finder' => ['Orchestra\Extension\Finder', 'Orchestra\Contracts\Extension\Finder'],
        'orchestra.extension' => ['Orchestra\Extension\Factory', 'Orchestra\Contracts\Extension\Factory'],
        'orchestra.form' => ['Orchestra\Html\Form\Factory', 'Orchestra\Contracts\Html\Form\Factory'],
        'orchestra.postal' => ['Orchestra\Notifier\Postal'],
        'orchestra.memory' => ['Orchestra\Memory\MemoryManager'],
        'orchestra.messages' => ['Orchestra\Messages\MessageBag', 'Orchestra\Contracts\Messages\MessageBag'],
        'orchestra.notifier' => ['Orchestra\Notifier\NotifierManager'],
        'orchestra.publisher' => ['Orchestra\Foundation\Publisher\PublisherManager'],
        'orchestra.meta' => ['Orchestra\Foundation\Meta'],
        'orchestra.table' => ['Orchestra\Html\Table\Factory', 'Orchestra\Contracts\Html\Table\Factory'],
        'orchestra.theme' => ['Orchestra\View\Theme\ThemeManager'],
        'orchestra.widget' => ['Orchestra\Widget\WidgetManager'],
    ];

    /**
     * List of core facades.
     *
     * @var array
     */
    protected $facades = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFoundation();

        $this->registerMetaContainer();

        $this->registerThrottlesLogins();

        $this->registerFacadesAliases();

        $this->registerCoreContainerAliases();

        $this->registerEventListeners();
    }

    /**
     * Register the service provider for foundation.
     *
     * @return void
     */
    protected function registerFoundation(): void
    {
        $this->app['orchestra.installed'] = false;

        $this->app->singleton('orchestra.app', function (Application $app) {
            return new Foundation($app, new RouteResolver($app));
        });
    }

    /**
     * Register the service provider for meta container.
     *
     * @return void
     */
    protected function registerMetaContainer(): void
    {
        $this->app->singleton('orchestra.meta', function () {
            return new Meta();
        });
    }

    /**
     * Register the service provider for foundation.
     *
     * @return void
     */
    protected function registerThrottlesLogins(): void
    {
        $config = $this->app->make('config')->get('orchestra/foundation::throttle', []);
        $throttles = $config['resolver'] ?? BasicThrottle::class;

        $this->app->bind(ThrottlesLogins::class, $throttles);

        BasicThrottle::setConfig($config);
    }

    /**
     * Register additional events for application.
     *
     * @return void
     */
    protected function registerEventListeners(): void
    {
        $this->app->terminating(function () {
            $this->app->make('events')->dispatch('orchestra.done');
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootAuthen();

        $path = \realpath(__DIR__.'/../../');

        $this->addConfigComponent('orchestra/foundation', 'orchestra/foundation', "{$path}/config");
        $this->addLanguageComponent('orchestra/foundation', 'orchestra/foundation', "{$path}/resources/lang");
        $this->addViewComponent('orchestra/foundation', 'orchestra/foundation', "{$path}/resources/views");
    }

    /**
     * Bootstrap authen.
     *
     * @return void
     */
    protected function bootAuthen(): void
    {
        $this->bootAuthenProvider();

        Authen::setIdentifierName('username');
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
