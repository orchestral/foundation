<?php namespace Orchestra\Widget;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider {
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('orchestra/foundation', 'orchestra/foundation');
	}
}