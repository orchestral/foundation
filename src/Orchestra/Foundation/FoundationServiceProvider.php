<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

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
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('orchestra/foundation', 'orchestra/foundation');

		$loader = AliasLoader::getInstance();
		$loader->alias('Orchestra\Asset', 'Orchestra\Support\Facades\Asset');
		$loader->alias('Orchestra\Acl', 'Orchestra\Support\Facades\Acl');
		$loader->alias('Orchestra\App', 'Orchestra\Support\Facades\App');
		$loader->alias('Orchestra\Config', 'Orchestra\Support\Facades\Config');
		$loader->alias('Orchestra\Extension', 'Orchestra\Support\Facades\Extension');
		$loader->alias('Orchestra\Form', 'Orchestra\Support\Facades\Form');
		$loader->alias('Orchestra\Mail', 'Orchestra\Support\Facades\Mail');
		$loader->alias('Orchestra\Memory', 'Orchestra\Support\Facades\Memory');
		$loader->alias('Orchestra\Messages', 'Orchestra\Support\Facades\Messages');
		$loader->alias('Orchestra\Resources', 'Orchestra\Support\Facades\Resources');
		$loader->alias('Orchestra\Site', 'Orchestra\Support\Facades\Site');
		$loader->alias('Orchestra\Table', 'Orchestra\Support\Facades\Table');
		$loader->alias('Orchestra\Theme', 'Orchestra\Support\Facades\Theme');
		$loader->alias('Orchestra\Widget', 'Orchestra\Support\Facades\Widget');

		include_once __DIR__."/../../start.php";
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.app', 'orchestra.installed');
	}
}
