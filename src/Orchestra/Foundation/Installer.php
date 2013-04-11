<?php namespace Orchestra\Foundation;

use PDOException,
	Illuminate\Foundation\Application,
	Illuminate\Support\Facades\DB;

class Installer {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = false;

	/**
	 * Construct a new instance.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Return whether Orchestra is installed
	 *
	 * @access public
	 * @return bool
	 */
	public function installed()
	{
		return $this->app['orchestra.installed'];
	}

	/**
	 * Check database connection
	 *
	 * @access public
	 * @return bool     return true if database successfully connected
	 */
	public function checkDatabase()
	{
		try
		{
			DB::connection()->getPdo();
			return true;
		}
		catch (PDOException $e)
		{
			return false;
		}
	}
}