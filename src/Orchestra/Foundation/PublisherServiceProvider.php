<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;

class PublisherServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerMigration();
		$this->registerAssetPublisher();
		$this->registerInstallationManager();
	}

	/**
	 * Register the service provider for Orchestra Platform migrator.
	 *
	 * @return void
	 */
	protected function registerMigration()
	{
		$this->app->make('migration.repository');
		
		$this->app['orchestra.migrator'] = $this->app->share(function ($app)
		{
			return new Publisher\Migration($app);
		});
	}

	/**
	 * Register the service provider for Orchestra Platform asset publisher.
	 *
	 * @return void
	 */
	protected function registerAssetPublisher()
	{
		$this->app['orchestra.publisher'] = $this->app->share(function ($app)
		{
			return new Publisher\Asset($app);
		});
	}

	/**
	 * Register the service provider for Orchestra Platform installation manager.
	 *
	 * @return void
	 */
	protected function registerInstallationManager()
	{
		$this->app['orchestra.installation.manager'] = $this->app->share(function ($app)
		{
			return new Installation\Manager($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.migrator', 'orchestra.publisher', 'orchestra.installation.manager');
	}
}