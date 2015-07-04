<?php namespace Orchestra\Foundation\Auth;

use Illuminate\Support\Arr;

abstract class ThrottlesLogins
{
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
