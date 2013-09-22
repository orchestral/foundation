<?php namespace Orchestra\Foundation;

use Illuminate\Mail\MailServiceProvder as ServiceProvider;

class MailServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var boolean
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
		parent::register();
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
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array_merge(array('orchestra.mail'), parent::provides());
	}
}
