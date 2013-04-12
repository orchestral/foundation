<?php namespace Orchestra\Widget;

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

		$this->registerInstaller();
	}

	/**
	 * Register `orchestra.installer` the service provider.
	 *
	 * @return void
	 */
	protected function registerInstaller()
	{
		$app = $this->app;

		$this->app['orchestra.installer'] = $this->app->share(function () use ($app)
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
		$this->package('orchestra/foundation', 'orchestra/foundation');

		include_once "../../start.php";
	}
}