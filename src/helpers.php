<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

if (! \function_exists('assetic')) {
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
            return \asset(\elixir($file, $buildDirectory));
        } catch (Exception $e) {
            return \asset($file);
        }
    }
}

if (! \function_exists('carbonize')) {
    /**
     * Parse string to Carbon instance.
     *
     * @param mixed  $datetime
     * @param string  $timezone
     *
     * @return \Carbon\CarbonInterface|null
     */
    function carbonize($datetime, string $timezone = 'UTC'): ?CarbonInterface
    {
        try {
            if ($datetime instanceof CarbonInterface) {
                return \use_timezone($datetime, $timezone);
            } elseif ($datetime instanceof DateTimeInterface) {
                return Date::instance($datetime)->timezone($timezone);
            } elseif (\is_array($datetime) && isset($datetime['date'])) {
                return Date::parse($datetime['date'], $datetime['timezone'] ?? 'UTC');
            } elseif (\is_string($datetime)) {
                return Date::parse($datetime, $timezone);
            }
        } catch (Exception $e) {
            //
        }

        return null;
    }
}

if (! \function_exists('get_meta')) {
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
        return \app('orchestra.meta')->get($key, $default);
    }
}

if (! \function_exists('handles')) {
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
        return \app('orchestra.app')->handles($name, $options);
    }
}

if (! \function_exists('memorize')) {
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
        return \app('orchestra.platform.memory')->get($key, $default);
    }
}

if (! \function_exists('orchestra')) {
    /**
     * Return orchestra.app instance.
     *
     * @param  string|null  $service
     *
     * @return mixed
     */
    function orchestra(?string $service = null)
    {
        if (\is_null($service)) {
            return \app('orchestra.app');
        }

        return \app("orchestra.platform.{$service}");
    }
}

if (! \function_exists('set_meta')) {
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
        return \app('orchestra.meta')->set($key, $value);
    }
}

if (! \function_exists('use_timezone')) {
    /**
     * Clone carbon and use different timezone.
     *
     * @param \Carbon\CarbonInterface  $carbon
     * @param string  $timezone
     *
     * @return \Carbon\CarbonInterface
     */
    function use_timezone(CarbonInterface $carbon, string $timezone): CarbonInterface
    {
        if ($carbon->timezone === $timezone) {
            return $carbon->copy();
        }

        return $carbon->copy()->timezone($timezone);
    }
}
