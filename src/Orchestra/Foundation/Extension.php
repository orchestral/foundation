<?php namespace Orchestra\Foundation;

class Extension {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * List of extensions.
	 *
	 * @var array
	 */
	protected $extensions = array();

	/**
	 * List of services.
	 *
	 * @var array
	 */
	protected $services = array();

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
	 * Start the extension.
	 *
	 * @access public	
	 * @param  string   $name
	 * @param  array    $config
	 * @return void
	 */
	public function start($name, $config)
	{
		if ( ! is_string($name)) return ;

		$this->services = array_merge($this->services, $config['services']);

		// by now, extension should already exist as an extension. We should
		// be able start orchestra.php start file on each package.
		if ($this->app['files']->isFile($file = rtrim($config['path'], '/').'/src/orchestra.php'))
		{
			$this->app['files']->getRequire($file);
		}

		$this->extensions[$name] = $config;

		$this->app['events']->fire("extension.started: {$name}");
	}

	/**
	 * Detect all extensions.
	 *
	 * @access public
	 * @return array
	 */
	public function detect()
	{
		$extensions = $this->app['orchestra.extension.finder']->detect();

		$this->app['orchestra.memory']->make()->put('extensions.available', $extensions);

		return $extensions;
	}
	
	/**
	 * Load active extension on boot.
	 *
	 * @access public
	 * @return void
	 */
	public function load()
	{
		$memory     = $this->app['orchestra.memory']->make();
		$availables = $memory->get('extensions.available', array());
		$actives    = $memory->get('extensions.active', array());

		foreach ($actives as $name => $config)
		{
			if (isset($availables[$name]))
			{
				$availables[$name]['config'] = array_merge(
					$availables[$name]['config'], 
					$config
				);

				$this->start($name, $availables[$name]);
			}
		}

		$this->app['orchestra.extension.provider']->services($this->services);
	}
}