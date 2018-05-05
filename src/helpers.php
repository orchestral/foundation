<?php


if (! function_exists('assetic')) {
    /**
     * Get the path to a versioned Elixir file or fallback to original file.
     *
     * @param  string  $file
     * @param  string  $buildDirectory
     *
     * @return string
     */
    function assetic(string $file, string $buildDirectory = 'build'): string
    {
        try {
            return asset(elixir($file, $buildDirectory));
        } catch (Exception $e) {
            return asset($file);
        }
    }
}

if (! function_exists('get_meta')) {
    /**
     * Get meta.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    function get_meta(string $key, $default = null)
    {
        return app('orchestra.meta')->get($key, $default);
    }
}

if (! function_exists('handles')) {
    /**
     * Return handles configuration for a package/app.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    function handles(string $name, array $options = []): string
    {
        return app('orchestra.app')->handles($name, $options);
    }
}

if (! function_exists('memorize')) {
    /**
     * Return memory configuration associated to the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     *
     * @see \Orchestra\Foundation\Foundation::memory()
     */
    function memorize(string $key, $default = null)
    {
        return app('orchestra.platform.memory')->get($key, $default);
    }
}

if (! function_exists('orchestra')) {
    /**
     * Return orchestra.app instance.
     *
     * @param  string|null  $service
     *
     * @return mixed
     */
    function orchestra(?string $service = null)
    {
        if (! is_null($service)) {
            return app("orchestra.platform.{$service}");
        }

        return app('orchestra.app');
    }
}

if (! function_exists('set_meta')) {
    /**
     * Set meta.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    function set_meta(string $key, $value = null)
    {
        return app('orchestra.meta')->set($key, $value);
    }
}
