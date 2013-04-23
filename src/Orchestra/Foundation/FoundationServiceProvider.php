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
		// Some dependencies is registered as deferred, by manually running 
		// make we ensure that these dependencies is booted.
		$this->app->make('filter.parser');
		$this->app->make('hash');

		include_once __DIR__."/../../start.php";
	}

	public function provides()
	{
		return array('orchestra.app', 'orchestra.site', 'orchestra.installed', 'orchestra.resources');
	}
}