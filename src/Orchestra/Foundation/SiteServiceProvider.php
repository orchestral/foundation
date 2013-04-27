<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider {

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
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.mail', 'orchestra.site');
	}
}
