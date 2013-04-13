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
		$this->registerExtensions();
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
		$this->app['orchestra.app'] = $this->app->share(function ($app)
		{
			return new Application($app);
		});

		$this->app['orchestra.extension'] = $this->app->share(function ($app)
		{
			return new Extension($app);
		});

		$this->app['orchestra.extension.finder'] = $this->app->share(function ($app)
		{
			return new Extension\Finder($app);
		});

		$this->app['orchestra.extension.provider'] = $this->app->share(function ($app)
		{
			return new Extension\ProviderRepository($app);
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