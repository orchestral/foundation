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

		$this->registerInstaller();
		$this->registerExtensionFinder();
	}

	/**
	 * Register `orchestra.installer` the service provider.
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
	 * Register `orchestra.extension.finder` the service provider.
	 *
	 * @return void
	 */
	protected function registerExtensionFinder()
	{
		$this->app['orchestra.extension.finder'] = $this->app->share(function ($app)
		{
			return new Extension\Finder($app);
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
}