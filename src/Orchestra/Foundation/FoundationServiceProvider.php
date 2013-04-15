<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider {
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() 
	{
		$this->app['orchestra.installed'] = false;

		$this->registerApplication();
		$this->registerInstaller();

		$this->package('orchestra/foundation', 'orchestra/foundation');
	}

	/**
	 * Register the service provider for Orchestra Platform Application.
	 * 
	 * @return void
	 */
	protected function registerApplication()
	{
		$this->app['orchestra.app'] = $this->app->share(function ($app)
		{
			return new Application($app);
		});

		$this->app['orchestra.site'] = $this->app->share(function ($app)
		{
			return new Site;
		});
	}

	/**
	 * Register the service provider for Orchestra Platform Installer.
	 *
	 * @return void
	 */
	protected function registerInstaller()
	{
		$this->app['orchestra.installer'] = $this->app->share(function ($app)
		{
			return new Installer($app);
		});
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

		include_once __DIR__."/../../start.php";
	}

	public function provides()
	{
		return array('orchestra.app', 'orchestra.site', 'orchestra.installer');
	}
}