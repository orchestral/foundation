<?php namespace Orchestra\Foundation\Extension;

class Finder {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;
	
	/**
	 * List of paths.
	 *
	 * @var array
	 */
	protected $paths = array();

	/**
	 * Construct a new finder.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
		$this->addPath(rtrim($app->make('path.base'), '/').'/vendor/');
	}

	/**
	 * Add a new path to finder.
	 *
	 * @access public	
	 * @param  string   $path
	 * @return void
	 */
	public function addPath($path)
	{
		if ( ! in_array($path, $this->paths)) $this->paths[] = $path;
	}

	/**
	 * Detect available extension.
	 *
	 * @access public
	 * @return array
	 */
	public function detect()
	{
		$extensions = array();

		foreach ($this->paths as $path)
		{
			foreach (glob("{$path}*/*/composer.json") as $manifest)
			{
				$extensions[] = $manifest;
			}
		}

		return $extensions;
	}
}