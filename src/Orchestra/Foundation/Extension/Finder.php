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
		$basePath  = rtrim($app['path.base'], '/');

		$this->addPath("{$basePath}/vendor/");
		$this->addPath("{$basePath}/workbench/");
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
			foreach ($this->app['files']->glob("{$path}*/*/orchestra.json") as $manifest)
			{
				list($vendor, $package) = $this->getPackageSegmentsFromManifest($manifest);

				if ( ! is_null($vendor) and ! is_null($package))
				{
					$extensions["{$vendor}/{$package}"] = $this->getManifestContents($manifest);
				}
			}
		}

		return $extensions;
	}

	/**
	 * Get manifest contents.
	 *
	 * @access protected
	 * @param  string   $manifest
	 * @return stdClass
	 */
	protected function getManifestContents($manifest)
	{
		$jsonable = json_decode($this->app['files']->get($manifest));

		if (is_null($jsonable))
		{
			throw new ManifestRuntimeException("Cannot decode file [{$manifest}]");
		}

		$jsonable->path        = $manifest;
		$jsonable->name        = (isset($jsonable->name) ? $jsonable->name : null);
		$jsonable->description = (isset($jsonable->description) ? $jsonable->description : null);
		$jsonable->version     = (isset($jsonable->version) ? $jsonable->version : '>0');
		$jsonable->config      = (isset($jsonable->config) ? $jsonable->config : array());
		$jsonable->require     = (isset($jsonable->require) ? $jsonable->require : array());
		$jsonable->services    = (isset($jsonable->services) ? $jsonable->services : array());

		return $jsonable;
	}

	/**
	 * Get package name from manifest.
	 * 
	 * @access protected
	 * @param  string   $manifest
	 * @return array
	 */
	protected function getPackageSegmentsFromManifest($manifest)
	{
		$vendor   = null;
		$package  = null; 
		$fragment = explode('/', $manifest);

		// Remove orchestra.json from fragment.
		array_pop($fragment);

		if (count($fragment) > 2)
		{
			$package = array_pop($fragment);
			$vendor  = array_pop($fragment);
		}

		return array($vendor, $package);
	}
}