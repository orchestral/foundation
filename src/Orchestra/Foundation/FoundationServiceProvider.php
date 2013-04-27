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

		$this->app['orchestra.app'] = $this->app->share(function ($app)
		{
			return new Application($app);
		});

		$this->app['orchestra.mail'] = $this->app->share(function ($app)
		{
			return new Mail($app);
		});

		$this->app['orchestra.site'] = $this->app->share(function ($app)
		{
			return new Site;
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
		return array('orchestra.app', 'orchestra.mail', 'orchestra.site', 'orchestra.installed');
	}
}
