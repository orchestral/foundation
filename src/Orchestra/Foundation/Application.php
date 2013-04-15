<?php namespace Orchestra\Foundation;

use Exception,
	PDOException;

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

		// Register filter parser so we can create dynamic routing.
		$this->app->make('filter.parser');

		// Make Menu instance for backend and frontend appliction
		$this->services['orchestra.menu'] = $app['orchestra.widget']->make('menu.orchestra');
		$this->services['app.menu']       = $app['orchestra.widget']->make('menu.app');
		$this->services['orchestra.acl']  = $app['orchestra.acl']->make('orchestra');

		try
		{
			// Initiate Memory class from App, this to allow advanced user
			// to use other implementation if there is a need for it.
			try 
			{
				$this->services['orchestra.memory'] = $app['orchestra.memory']->make();
			}
			catch (PDOException $e)
			{
				throw new Exception($e);
			}

			if (is_null($this->services['orchestra.memory']->get('site.name')))
			{
				throw new Exception('Installation is not completed');
			}

			// In event where we reach this point, we can consider no
			// exception has occur, we should be able to compile acl and
			// menu configuration
			$this->services['orchestra.acl']->attach($this->services['orchestra.memory']);

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
			$this->services['orchestra.memory'] = $app->make('orchestra.memory')
													->driver('runtime.orchestra');
			$this->services['orchestra.memory']->put('site.name', 'Orchestra');

			$this->services['orchestra.menu']->add('install')
				->title('Install')
				->link('orchestra::installer');

			$app['orchestra.installed'] = false;
		}

		$app['events']->fire('orchestra.started');
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