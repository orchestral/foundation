<?php namespace Orchestra\Foundation\Auth;

use Illuminate\Support\Arr;

abstract class ThrottlesLogins
{
    /**
     * The configurations.
     *
     * @var array
     */
    protected static $config = [
        'attempts'   => 5,
        'locked_for' => 60,
    ];

    /**
     * Set configuration.
     *
     * @param  array  $config
     *
     * @return void
     */
    public static function setConfig(array $config)
    {
        static::$config = array_merge(static::$config, $config);
    }

    /**
     * Get the maximum number of login attempts for delaying further attempts.
     *
     * @return int
     */
    protected function maxLoginAttempts()
    {
        return Arr::get(static::$config, 'attempts', 5);
    }

    /**
     * The number of seconds to delay further login attempts.
     *
     * @return int
     */
    protected function lockoutTime()
    {
        return Arr::get(static::$config, 'locked_for', 60);
    }

    /**
     * Get the login key.
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getLoginKey(array $input)
    {
        return implode('', Arr::only($input, ['email', '_ip']));
    }
}
