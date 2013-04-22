<?php namespace Orchestra\Foundation\Installation;

class Manager {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Construct a new instance.
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
	 * Run basic installation.
	 *
	 * @access public
	 * @return true
	 */
	public function install()
	{
		$migrator = $this->app->make('orchestra.migrator');

		$migrator->foundation();
	}
}