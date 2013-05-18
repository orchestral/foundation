<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Orchestra\Model\User;

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
		$this->registerMailer();
		$this->registerPublisher();
		$this->registerSite();
		$this->registerUserEloquent();
		$this->registerAliases();
	}

	/**
	 * Register the service provider for mail.
	 *
	 * @return void
	 */
	protected function registerMailer()
	{
		$this->app['orchestra.mail'] = $this->app->share(function ($app)
		{
			return new Mail($app);
		});
	}

	/**
	 * Register the service provider for publisher.
	 *
	 * @return void
	 */
	protected function registerPublisher()
	{
		$this->app['orchestra.publisher'] = $this->app->share(function ($app)
		{
			return new Publisher\PublisherManager($app);
		});
	}

	/**
	 * Register the service provider for site.
	 *
	 * @return void
	 */
	protected function registerSite()
	{
		$this->app['orchestra.site'] = $this->app->share(function ($app)
		{
			return new Site($app);
		});
	}

	/**
	 * Register the service provider for user.
	 *
	 * @return void
	 */
	protected function registerUserEloquent()
	{
		$this->app['orchestra.user'] = $this->app->share(function ($app)
		{
			return new User;
		});
	}

	/**
	 * Register aliases.
	 *
	 * @return void
	 */
	protected function registerAliases()
	{
		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('Orchestra\Mail', 'Orchestra\Support\Facades\Mail');
			$loader->alias('Orchestra\Publisher', 'Orchestra\Support\Facades\Publisher');
			$loader->alias('Orchestra\Site', 'Orchestra\Support\Facades\Site');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.mail', 'orchestra.publisher', 'orchestra.site', 'orchestra.user');
	}
}
