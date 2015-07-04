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
     * Get the login attempts cache key.
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getLoginAttemptsKey(array $input)
    {
        $key = implode('', Arr::only($input, ['username', '_ip']));

        return sprintf('login:attempts:%s', md5($key));
    }

    /**
     * Get the login lock cache key.
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getLoginLockExpirationKey(array $input)
    {
        $key = implode('', Arr::only($input, ['username', '_ip']));

        return sprintf('login:expiration:%s', md5($key));
    }
}
