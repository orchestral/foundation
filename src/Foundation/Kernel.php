<?php namespace Orchestra\Foundation;

use Closure;
use Exception;
use Orchestra\Memory\Provider;
use Orchestra\Http\RouteManager;
use Orchestra\Extension\RouteGenerator;

class Kernel extends RouteManager
{
    /**
     * Booted indicator.
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Get acl services.
     *
     * @var \Orchestra\Auth\Acl\Acl
     */
    public function acl()
    {
        return $this->app['orchestra.platform.acl'];
    }

    /**
     * Start the application.
     *
     * @return Application
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
     * Get installation status.
     *
     * @return bool
     */
    public function installed()
    {
        return $this->app['orchestra.installed'];
    }

    /**
     * Get memory services.
     *
     * @var \Orchestra\Memory\Provider
     */
    public function memory()
    {
        return $this->app['orchestra.platform.memory'];
    }

    /**
     * Get menu services.
     *
     * @var \Orchestra\Widget\MenuWidgetHandler
     */
    public function menu()
    {
        return $this->app['orchestra.platform.menu'];
    }

    /**
     * Register the given Closure with the "group" function namespace set.
     *
     * @param  string|null      $namespace
     * @param  \Closure|null    $callback
     * @return void
     */
    public function namespaced($namespace, Closure $callback)
    {
        $attributes = [];

        if (! empty($namespace) && $namespace != '\\') {
            $attributes['namespace'] = $namespace;
        }

        $this->group('orchestra/foundation', 'orchestra', $attributes, $callback);
    }

    /**
     * Get extension route.
     *
     * @param  string   $name
     * @param  string   $default
     * @return \Orchestra\Contracts\Extension\RouteGenerator
     */
    public function route($name, $default = '/')
    {
        // Boot the application.
        $this->boot();

        if (in_array($name, ['orchestra', 'orchestra/foundation'])) {
            $name = 'orchestra';
        }

        return parent::route($name, $default);
    }

    /**
     * Boot application.
     *
     * @return void
     */
    protected function bootApplication()
    {
        $this->registerBaseServices();

        try {
            $memory = $this->bootInstalledApplication();
        } catch (Exception $e) {
            $memory = $this->bootNewApplication();
        }

        $this->app->instance('orchestra.platform.memory', $memory);

        $this->registerComponents($memory);

        $this->app['events']->fire('orchestra.started', [$memory]);
    }

    /**
     * Run booting on installed application.
     *
     * @return \Orchestra\Memory\Provider
     * @throws \Exception
     */
    protected function bootInstalledApplication()
    {
        // Initiate Memory class from App, this to allow advanced user
        // to use other implementation if there is a need for it.
        $memory = $this->app['orchestra.memory']->make();

        if (is_null($memory->get('site.name'))) {
            throw new Exception('Installation is not completed');
        }

        // In event where we reach this point, we can consider no
        // exception has occur, we should be able to compile acl and
        // menu configuration
        $this->acl()->attach($memory);

        // In any event where Memory failed to load, we should set
        // Installation status to false routing for installation is
        // enabled.
        $this->app['orchestra.installed'] = true;

        $this->createAdminMenu();

        return $memory;
    }

    /**
     * Run booting on new application.
     *
     * @return \Orchestra\Memory\Provider
     */
    protected function bootNewApplication()
    {
        // In any case where Exception is catched, we can be assure that
        // Installation is not done/completed, in this case we should
        // use runtime/in-memory setup
        $memory = $this->app['orchestra.memory']->make('runtime.orchestra');
        $memory->put('site.name', 'Orchestra Platform');

        $this->menu()->add('install')
            ->title('Install')
            ->link($this->handles('orchestra::install'));

        $this->app['orchestra.installed'] = false;

        return $memory;
    }

    /**
     * Create Administration Menu for Orchestra Platform.
     *
     * @return void
     */
    protected function createAdminMenu()
    {
        $menu    = $this->menu();
        $handler = 'Orchestra\Foundation\AdminMenuHandler';

        $menu->add('home')
            ->title($this->app['translator']->get('orchestra/foundation::title.home'))
            ->link($this->handles('orchestra::/'));

        $this->app['events']->listen('orchestra.ready: admin', $handler);
    }

    /**
     * Register base application services.
     *
     * @return void
     */
    protected function registerBaseServices()
    {
        $this->app->instance('orchestra.platform.menu', $this->app['orchestra.widget']->make('menu.orchestra'));
        $this->app->instance('orchestra.platform.acl', $this->app['orchestra.acl']->make('orchestra'));

        $this->app->instance('app.menu', $this->app['orchestra.widget']->make('menu.app'));
    }

    /**
     * Register base application components.
     *
     * @param  \Orchestra\Memory\Provider  $memory
     * @return void
     */
    protected function registerComponents(Provider $memory)
    {
        $this->app['orchestra.notifier']->setDefaultDriver('orchestra');
        $this->app['orchestra.mail']->attach($memory);
    }

    /**
     * Magic method to get services.
     *
     * @param  string   $method
     * @param  array    $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->app, $method], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateRouteByName($name, $default)
    {
        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (in_array($name, ['orchestra'])) {
            return new RouteGenerator(
                $this->app['config']->get('orchestra/foundation::handles', $default),
                $this->app['request']
            );
        }

        return parent::generateRouteByName($name, $default);
    }
}
