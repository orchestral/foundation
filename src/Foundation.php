<?php

namespace Orchestra\Foundation;

use Closure;
use Exception;
use Orchestra\Http\RouteManager;
use Orchestra\Contracts\Extension\UrlGenerator;
use Orchestra\Contracts\Authorization\Authorization;
use Orchestra\Foundation\Http\Handlers\UserMenuHandler;
use Orchestra\Contracts\Memory\Provider as MemoryProvider;
use Orchestra\Foundation\Http\Handlers\SettingMenuHandler;
use Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler;
use Orchestra\Contracts\Foundation\Foundation as FoundationContract;
use Orchestra\Contracts\Foundation\Application as ApplicationContract;

class Foundation extends RouteManager implements FoundationContract
{
    /**
     * Booted indicator.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The config repository implementation.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Foundation services.
     *
     * @var array
     */
    protected $services = [];

    /**
     * The widget manager.
     *
     * @var \Orchestra\Widget\WidgetManager|null
     */
    protected $widget;

    /**
     * Construct a new instance.
     *
     * @param  \Orchestra\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Foundation\RouteResolver  $resolver
     */
    public function __construct(ApplicationContract $app, RouteResolver $resolver)
    {
        $this->flush();

        parent::__construct($app, $resolver);

        $this->config = $app->make('config');

        if ($app->bound('orchestra.widget')) {
            $this->widget = $app->make('orchestra.widget');
        }
    }

    /**
     * Start the application.
     *
     * @return $this
     */
    public function boot()
    {
        if (! $this->booted) {
            // Mark the application as booted and boot the application.
            $this->booted = true;

            $this->bootApplication();
        }

        return $this;
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush(): void
    {
        $this->booted = false;
        $this->config = null;
        $this->widget = null;

        $this->services = [
            'acl' => null,
            'memory' => null,
            'menu' => null,
        ];
    }

    /**
     * Get installation status.
     *
     * @return bool
     */
    public function installed(): bool
    {
        return $this->app->make('orchestra.installed');
    }

    /**
     * Get acl services.
     *
     * @return \Orchestra\Contracts\Authorization\Authorization|null
     */
    public function acl(): ?Authorization
    {
        return $this->services['acl'];
    }

    /**
     * Get memory services.
     *
     * @return \Orchestra\Contracts\Memory\Provider|null
     */
    public function memory(): ?MemoryProvider
    {
        return $this->services['memory'];
    }

    /**
     * Get menu services.
     *
     * @return \Orchestra\Widget\Handlers\Menu|null
     */
    public function menu()
    {
        return $this->services['menu'];
    }

    /**
     * Get widget services by type.
     *
     * @param  string  $type
     *
     * @return \Orchestra\Widget\Handler|null
     */
    public function widget(string $type)
    {
        return ! is_null($this->widget)
                    ? $this->widget->make("{$type}.orchestra")
                    : null;
    }

    /**
     * Register the given Closure with the "group" function namespace set.
     *
     * @param  string|null  $namespace
     * @param  array|\Closure  $attributes
     * @param  \Closure|null  $callback
     *
     * @return void
     */
    public function namespaced($namespace, $attributes = [], Closure $callback = null): void
    {
        if ($attributes instanceof Closure) {
            $callback = $attributes;
            $attributes = [];
        }

        if (! empty($namespace) && $namespace != '\\') {
            $attributes['namespace'] = $namespace;
        }

        $attributes['middleware'] = array_unique(array_merge(
            $attributes['middleware'] ?? [], ['orchestra']
        ));

        $this->group('orchestra/foundation', 'orchestra', $attributes, $callback);
    }

    /**
     * Get extension route.
     *
     * @param  string  $name
     * @param  string  $default
     *
     * @return \Orchestra\Contracts\Extension\UrlGenerator
     */
    public function route(string $name, string $default = '/'): UrlGenerator
    {
        // Boot the application.
        $this->boot();

        return parent::route($name, $default);
    }

    /**
     * Boot application.
     *
     * @return void
     */
    protected function bootApplication(): void
    {
        $this->registerBaseServices();

        try {
            $memory = $this->bootInstalledApplication();
        } catch (Exception $e) {
            $memory = $this->bootNewApplication();
        }

        $this->services['memory'] = $memory;
        $this->app->instance('orchestra.platform.memory', $memory);

        $this->registerComponents($memory);

        $this->app->make('events')->dispatch('orchestra.started', [$memory]);
    }

    /**
     * Run booting on installed application.
     *
     * @throws \Exception
     *
     * @return \Orchestra\Contracts\Memory\Provider
     */
    protected function bootInstalledApplication(): MemoryProvider
    {
        // Initiate Memory class from App, this to allow advanced user
        // to use other implementation if there is a need for it.
        $memory = $this->app->make('orchestra.memory')->make();

        $name = $memory->get('site.name');

        if (is_null($name)) {
            throw new Exception('Installation is not completed');
        }

        $this->config->set('app.name', $name);

        // In event where we reach this point, we can consider no
        // exception has occur, we should be able to compile acl and
        // menu configuration
        $this->acl()->attach($memory);

        // In any event where Memory failed to load, we should set
        // Installation status to false routing for installation is
        // enabled.
        $this->app->instance('orchestra.installed', true);

        $this->createAdminMenu();

        return $memory;
    }

    /**
     * Run booting on new application.
     *
     * @return \Orchestra\Contracts\Memory\Provider
     */
    protected function bootNewApplication(): MemoryProvider
    {
        // In any case where Exception is catched, we can be assure that
        // Installation is not done/completed, in this case we should
        // use runtime/in-memory setup
        $memory = $this->app->make('orchestra.memory')->make('runtime.orchestra');
        $memory->put('site.name', 'Orchestra Platform');

        $this->menu()->add('install')
            ->title('Install')
            ->link($this->handles('orchestra::install'))
            ->icon('power-off');

        $this->app->instance('orchestra.installed', false);

        return $memory;
    }

    /**
     * Create Administration Menu for Orchestra Platform.
     *
     * @return void
     */
    protected function createAdminMenu(): void
    {
        $menu = $this->menu();
        $events = $this->app->make('events');

        $handlers = [
            UserMenuHandler::class,
            ExtensionMenuHandler::class,
            SettingMenuHandler::class,
        ];

        $menu->add('home')
            ->title($this->app->make('translator')->get('orchestra/foundation::title.home'))
            ->link($this->handles('orchestra::/'))
            ->icon('home');

        foreach ($handlers as $handler) {
            $events->listen('orchestra.started: admin', $handler);
        }
    }

    /**
     * Register base application services.
     *
     * @return void
     */
    protected function registerBaseServices(): void
    {
        $this->services['acl'] = $this->app->make('orchestra.acl')->make('orchestra');
        $this->app->instance('orchestra.platform.acl', $this->services['acl']);

        $this->services['menu'] = $this->widget('menu');
        $this->app->instance('orchestra.platform.menu', $this->services['menu']);
    }

    /**
     * Register base application components.
     *
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     *
     * @return void
     */
    protected function registerComponents(MemoryProvider $memory): void
    {
        $this->app->make('orchestra.notifier')->setDefaultDriver('orchestra');
        $this->app->make('orchestra.mail')->attach($memory);
    }

    /**
     * Magic method to get services.
     *
     * @param  string   $method
     * @param  array    $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->app->{$method}(...$parameters);
    }
}
