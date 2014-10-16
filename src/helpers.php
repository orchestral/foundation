<?php

if (! function_exists('orchestra')) {
    /**
     * Return orchestra.app instance.
     *
     * @param  string|null  $service
     * @return mixed
     */
    function orchestra($service = null)
    {
        if (! is_null($service)) {
            return app("orchestra.platform.{$service}");
        }

        return app('orchestra.app');
    }
}

if (! function_exists('memorize')) {
    /**
     * Return memory configuration associated to the request.
     *
     * @param  string   $key
     * @param  string   $default
     * @return mixed
     * @see    \Orchestra\Foundation\Kernel::memory()
     */
    function memorize($key, $default = null)
    {
        return app('orchestra.platform.memory')->get($key, $default);
    }
}

if (! function_exists('handles')) {
    /**
     * Return handles configuration for a package/app.
     *
     * @param  string   $name   Route
     * @return string
     */
    function handles($name)
    {
        return app('orchestra.app')->handles($name);
    }
}

if (! function_exists('resources')) {
    /**
     * Return resources route.
     *
     * @param  string   $name   Route
     * @return string
     */
    function resources($name)
    {
        $name = ltrim($name, '/');

        return app('orchestra.app')->handles("orchestra/foundation::resources/{$name}");
    }
}
