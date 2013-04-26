<?php

if ( ! function_exists('memorize'))
{
	/**
	 * Return memory configuration associated to the request
	 *
	 * @see    Orchestra\Core::memory()
	 * @param  string   $key
	 * @param  string   $default
	 * @return mixed
	 */
	function memorize($key, $default = null)
	{
		return Orchestra\Support\Facades\App::memory()->get($key, $default);
	}
}

if ( ! function_exists('handles'))
{
	/**
	 * Return handles configuration for a bundle
	 *
	 * @param  string   $bundle Bundle name
	 * @return string           URL path
	 */
	function handles($name)
	{
		$resolver = new Illuminate\Support\NamespacedItemResolver;
		$handles  = '';
		$query    = '';

		// split URI and query string.
		if (strpos($name, '?') !== false) list($name, $query) = explode('?', $name, 2);

		list($package, $route) = $resolver->parseKey($name);

		empty($route) and $route = '';

		if (empty($package)) $package = "app";

		$handles = Orchestra\Support\Facades\App::route($package);

		// reappend query string.
		empty($query) or $route = "{$route}?{$query}";
		$handles = trim("{$handles}/{$route}", "/");

		empty($handles) and $handles = '/';

		return url($handles);
	}
}