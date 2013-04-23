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
		$this->registerResources();
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
	 * Register the service provider for Orchestra Platform Resources.
	 *
	 * @return void
	 */
	protected function registerResources()
	{
		$this->app['orchestra.resources'] = $this->app->share(function ($app)
		{
			return new Resources\Environment($app);
		});
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('orchestra/foundation', 'orchestra/foundation');

		include_once __DIR__."/../../start.php";
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.app', 'orchestra.site', 'orchestra.installed', 'orchestra.resources');
	}
}