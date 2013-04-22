<?php namespace Orchestra\Foundation\Publisher;

class Migration {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Migrator instance.
	 *
	 * @var Illuminate\Database\Migrations\Migrator
	 */
	protected $migrator = null;

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

		// Boot migration dependency
		$this->migrator = $this->app->make('migrator');
	}

	/**
	 * Create migration repository if it's not available.
	 *
	 * @access protected
	 * @return void
	 */
	protected function createMigrationRepository()
	{
		$repository = $this->migrator->getRepository();

		if ( ! $repository->repositoryExists()) $repository->createRepository();
	}

	/**
	 * Run migration for an extension or application.
	 *
	 * @access public	
	 * @param  string   $path
	 * @return void
	 */
	public function run($path)
	{
		// We need to make sure migration table is available.
		$this->createMigrationRepository();

		$this->migrator->run($path);
	}

	/**
	 * Migration Extension.
	 *
	 * @access public
	 * @return void
	 */
	public function extension($name)
	{
		$path = $this->app['orchestra.extension']->option($name, 'path');
		
		 ! is_null($path) and $this->migrator->run($path);
	}

	/**
	 * Migration Orchestra Platform.
	 *
	 * @access public
	 * @return void
	 */
	public function foundation()
	{
		$basePath = rtrim($this->app['path.base'], '/');

		$this->migrator->run("{$basePath}/vendor/orchestra/memory/src/migrations/");
		$this->migrator->run("{$basePath}/vendor/orchestra/auth/src/migrations/");
	}
}