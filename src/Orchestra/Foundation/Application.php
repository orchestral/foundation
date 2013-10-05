<?php namespace Orchestra\Foundation;

use Exception;
use Illuminate\Support\NamespacedItemResolver;
use Orchestra\Extension\RouteGenerator;

class Application {

	/**
	 * Application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * List of services.
	 *
	 * @var array
	 */
	public $services = array();

	/**
	 * List of routes.
	 *
	 * @var array
	 */
	public $routes = array();

	/**
	 * Booted indicator.
	 *
	 * @var boolean
	 */
	protected $booted = false;

	/**
	 * Construct a new Application instance.
	 *
	 * @param  \Illuminate\Foundation\Application   $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Start the application.
	 *
	 * @return self
	 */
	public function boot()
	{
		if ($this->booted) return $this;

		// Set the indicator that Application has been booted.
		$this->booted = true;

		$app    = $this->app;
		$memory = null;

		// Make Menu instance for backend and frontend appliction
		$this->services['orchestra.menu'] = $app['orchestra.widget']->make('menu.orchestra');
		$this->services['app.menu']       = $app['orchestra.widget']->make('menu.app');
		$this->services['orchestra.acl']  = $app['orchestra.acl']->make('orchestra');

		try
		{
			// Initiate Memory class from App, this to allow advanced user
			// to use other implementation if there is a need for it.
			$memory = $app['orchestra.memory']->make();

			if (is_null($memory->get('site.name')))
			{
				throw new Exception('Installation is not completed');
			}

			// In event where we reach this point, we can consider no
			// exception has occur, we should be able to compile acl and
			// menu configuration
			$this->services['orchestra.acl']->attach($memory);

			// In any event where Memory failed to load, we should set
			// Installation status to false routing for installation is
			// enabled.
			$app['orchestra.installed'] = true;

			$this->createAdminMenu();
		}
		catch (Exception $e)
		{
			// In any case where Exception is catched, we can be assure that
			// Installation is not done/completed, in this case we should
			// use runtime/in-memory setup
			$memory = $app['orchestra.memory']->make('runtime.orchestra');
			$memory->put('site.name', 'Orchestra Platform');

			$this->services['orchestra.menu']->add('install')
				->title('Install')
				->link($this->handles('orchestra::install'));

			$app['orchestra.installed'] = false;
		}

		$this->services['orchestra.memory'] = $memory;
		$app['events']->fire('orchestra.started');

		return $this;
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
	 * Get Application instance.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function illuminate()
	{
		return $this->app;
	}

	/**
	 *  Return locate handles configuration for a package/app.
	 *
	 * @param  string   $name   Package name
	 * @return string
	 */
	public function locate($name)
	{
		$path  = '';
		$query = '';

		// split URI and query string, the route resolver should not worry 
		// about provided query string.
		if (strpos($name, '?') !== false) list($name, $query) = explode('?', $name, 2);

		list($package, $route, $item) = with(new NamespacedItemResolver)->parseKey($name);

		! empty($item) and $route = "{$route}.{$item}";

		// Prepare route valid, since we already extract package from route 
		// we can re append query string to route value.
		empty($route) and $route = '';
		empty($query) or $route = "{$route}?{$query}";

		// If package is empty, we should consider that the route is using
		// app (or root path), it doesn't matter at this stage if app is 
		// an extension or simply handling root path.
		if (empty($package)) $package = "app";

		// Get the path from route configuration, and append route.
		$path = $this->route($package)->to($route);
		empty($path) and $path = '/';

		return $path;
	}

	/**
	 *  Return handles URL for a package/app.
	 *
	 * @param  string   $name   Package name
	 * @return string
	 */
	public function handles($path)
	{
		$locate = $this->locate($path);

		if (starts_with($locate, 'http')) return $locate;

		return $this->app['url']->to($locate);
	}

	/**
	 * Return route group dispatch for a package/app.
	 *
	 * @param  string   $name   Package name
	 * @return string
	 */
	public function group($name, $default, $group = array())
	{
		$route = $this->route($name, $default);

		return array_merge($group, array(
			'prefix' => $route->prefix(),
			'domain' => $route->domain(),
		));
	}

	/**
	 * Get extension handle.
	 *
	 * @param  string   $name
	 * @param  string   $default
	 * @return string
	 */
	public function route($name, $default = '/')
	{
		// Boot the application.
		$this->boot();

		if (isset($this->routes[$name])) return $this->routes[$name];

		$route = null;

		// Orchestra Platform routing is managed by `orchestra/foundation::handles`
		// and can be manage using configuration. 
		if ( ! in_array($name, array('orchestra', 'orchestra/foundation')))
		{
			$route = $this->app['orchestra.extension']->route($name, $default);
		}
		else
		{			
			$name  = 'orchestra';
			$route = new RouteGenerator(
				$this->app['config']->get('orchestra/foundation::handles', $default),
				$this->app['request']->root(),
				$this->app['request']->secure()				
			);
		}

		return $this->routes[$name] = $route;
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
		$passtru = array('make', 'abort');

		// Allow Orchestra\Foundation\Application to called method available 
		// in Illuminate\Foundation\Application without any issue.
		if (in_array($method, $passtru))
		{
			return call_user_func_array(array($this->app, $method), $parameters);
		}

		$action = (count($parameters) < 1 ? "orchestra" : array_shift($parameters));
		$method = "{$action}.{$method}";

		return (isset($this->services[$method]) ? $this->services[$method] : null);
	}	

	/**
	 * Create Administration Menu for Orchestra Platform.
	 *
	 * @return void
	 */
	protected function createAdminMenu()
	{
		$menu = $this->services['orchestra.menu'];

		$menu->add('home')
			->title($this->app['translator']->get('orchestra/foundation::title.home'))
			->link($this->handles('orchestra::/'));

		$this->app['events']->listen('orchestra.ready: admin', 'Orchestra\Foundation\Services\AdminMenuHandler');
	}
}
