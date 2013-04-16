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
		return Orchestra\App::memory()->get($key, $default);
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

		if (empty($route)) $route = '/';

		if ( ! is_null($package)) $handles = Config::get("{$package}::handles", '/');

		if ( ! is_string($handles)) $handles = '/';
		
		$route = ltrim($route, '/');

		// reappend query string.
		empty($query) or $route = "{$route}?{$query}";

		$handles = "{$handles}/{$route}";

		return url(rtrim($handles, "/"));
	}
}