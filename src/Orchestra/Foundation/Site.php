<?php namespace Orchestra\Foundation;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Site {

	/**
	 * Data for site.
	 *
	 * @var array
	 */
	protected $items = array();

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
		if ( ! ($datetime instanceof DateTime))
		{
			$datetime = new DateTime(
				$datetime, 
				new DateTimeZone(Config::get('app.timezone', 'UTC'))
			);
		}

		if (Auth::guest()) return $datetime;

		return Auth::user()->localtime($datetime);
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
