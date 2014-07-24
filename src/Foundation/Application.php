<?php namespace Orchestra\Foundation;

use Exception;
use Orchestra\Foundation\Abstractable\RouteManager;
use Orchestra\Memory\Provider;

class Application extends RouteManager
{
    /**
     * Booted indicator.
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Passtru method for Illuminate\Foundation\Application.
     *
     * @var array
     */
    protected $passtru = array('abort', 'bound', 'make');

    /**
     * List of services.
     *
     * @var array
     */
    public $services = array();

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
     * Create Administration Menu for Orchestra Platform.
     *
     * @return void
     */
    protected function createAdminMenu()
    {
        $menu    = $this->services['orchestra.menu'];
        $handler = 'Orchestra\Foundation\AdminMenuHandler';

        $menu->add('home')
            ->title($this->app['translator']->get('orchestra/foundation::title.home'))
            ->link($this->handles('orchestra::/'));

        $this->app['events']->listen('orchestra.ready: admin', $handler);
    }

    /**
     * Get Application instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function illuminate()
    {
        return $this->app;
    }

    /**
     * Get installation status.
     *
     * @return boolean
     */
    public function installed()
    {
        return $this->app['orchestra.installed'];
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

        $this->services['orchestra.memory'] = $memory;

        $this->registerComponents($memory);

        $this->app['events']->fire('orchestra.started', array($memory));
    }

    /**
     * Run booting on installed application.
     *
     * @return \Orchestra\Memory\Provider
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
        $this->services['orchestra.acl']->attach($memory);

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

        $this->services['orchestra.menu']->add('install')
            ->title('Install')
            ->link($this->handles('orchestra::install'));

        $this->app['orchestra.installed'] = false;

        return $memory;
    }

    /**
     * Register base application services.
     *
     * @return void
     */
    protected function registerBaseServices()
    {
        $this->services['orchestra.menu'] = $this->app['orchestra.widget']->make('menu.orchestra');
        $this->services['app.menu']       = $this->app['orchestra.widget']->make('menu.app');
        $this->services['orchestra.acl']  = $this->app['orchestra.acl']->make('orchestra');
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
        // Allow Orchestra\Foundation\Application to called method available
        // in Illuminate\Foundation\Application without any issue.
        if (in_array($method, $this->passtru)) {
            return call_user_func_array(array($this->app, $method), $parameters);
        }

        $action = (count($parameters) < 1 ? "orchestra" : array_shift($parameters));
        $method = "{$action}.{$method}";

        return array_get($this->services, $method);
    }
}
