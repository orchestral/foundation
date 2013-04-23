<?php namespace Orchestra\Foundation;

use Exception;

class Application {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * List of services.
	 *
	 * @var array
	 */
	public $services = array();

	/**
	 * Construct a new Application instance.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Start the application.
	 *
	 * @access public
	 * @return void
	 */
	public function start()
	{
		$app = $this->app;

		// Make Menu instance for backend and frontend appliction
		$this->services['orchestra.menu'] = $app['orchestra.widget']->make('menu.orchestra');
		$this->services['app.menu']       = $app['orchestra.widget']->make('menu.app');
		$this->services['orchestra.acl']  = $app['orchestra.acl']->make('orchestra');

		$memory = null;

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
		}
		catch (Exception $e)
		{
			// In any case where Exception is catched, we can be assure that
			// Installation is not done/completed, in this case we should
			// use runtime/in-memory setup
			$memory = $app['orchestra.memory']->make('runtime.orchestra');
			$memory->put('site.name', 'Orchestra');

			$this->services['orchestra.menu']->add('install')
				->title('Install')
				->link(handles('orchestra/foundation::install'));

			$app['orchestra.installed'] = false;
		}

		$this->services['orchestra.memory'] = $memory;
		$app['events']->fire('orchestra.started');
	}

	/**
	 * Get installation status.
	 *
	 * @access public
	 * @return boolean
	 */
	public function installed()
	{
		return $this->app['orchestra.installed'];
	}

	/**
	 * Magic method to get services.
	 */
	public function __call($method, $parameters)
	{
		$action = (count($parameters) < 1 ? "orchestra" : array_shift($parameters));
		$method = "{$action}.{$method}";

		return (isset($this->services[$method]) ? $this->services[$method] : null);
	}
}