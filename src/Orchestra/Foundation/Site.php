<?php namespace Orchestra\Foundation;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Site {
	
	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Data for site.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Construct a new instance.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Boot the instance.
	 *
	 * @access public
	 * @return void
	 */
	public function boot()
	{
		$redirect = $this->app['request']->input('redirect');

		if ( ! is_null($redirect))
		{
			$this->app['session']->flash('orchestra.redirect', $redirect);
		}
	}

	/**
	 * Get a site value.
	 *
	 * @access public 	
	 * @param  string   $key
	 * @param  mixed    $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return array_get($this->items, $key, $default);
	}

	/**
	 * Set a site value.
	 *
	 * @access public 	
	 * @param  string   $key
	 * @param  mixed    $value
	 * @return mixed
	 */
	public function set($key, $value = null)
	{
		return array_set($this->items, $key, $value);
	}

	/**
	 * Check if site key has a value.
	 *
	 * @access public 	
	 * @param  string   $key
	 * @return bool
	 */
	public function has($key)
	{
		return ! is_null($this->get($key));
	}

	/**
	 * Remove a site key.
	 *
	 * @static
	 * @access public
	 * @param  string   $key
	 * @return void
	 */
	public function forget($key)
	{
		return array_forget($this->items, $key);
	}

	/**
	 * Convert given time to user localtime, however if it a guest user 
	 * return based on default timezone.
	 *
	 * @static
	 * @access public
	 * @param  mixed    $datetime
	 * @return DateTime
	 */
	public function localtime($datetime)
	{
		$app         = $this->app;
		$appTimeZone = $app['config']->get('app.timezone', 'UTC');

		if ( ! ($datetime instanceof DateTime))
		{
			$datetime = new DateTime(
				$datetime, 
				new DateTimeZone($appTimeZone)
			);
		}

		if ($app['auth']->guest()) return $datetime;

		$userId       = $app['auth']->user()->id;
		$userMeta     = $app['orchestra.memory']->make('user');
		$userTimeZone = $userMeta->get("timezone.{$userId}", $appTimeZone);

		$datetime->setTimeZone(new DateTimeZone($userTimeZone));

		return $datetime;
	}

	/**
	 * Get all available items.
	 *
	 * @access public
	 * @return array
	 */
	public function all()
	{
		return $this->items;
	}
}
