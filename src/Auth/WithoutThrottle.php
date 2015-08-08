<?php namespace Orchestra\Foundation\Auth;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as Command;

class WithoutThrottle extends ThrottlesLogins implements Command
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  array  $input
     *
     * @return bool
     */
    public function hasTooManyLoginAttempts(array $input)
    {
        return false;
    }

    /**
     * Get total seconds before doing another login attempts for the user.
     *
     * @param  array  $input
     *
     * @return int
     */
    public function getSecondsBeforeNextAttempts(array $input)
    {
        return Arr::get(static::$config, 'locked_for', 60);
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  array  $input
     *
     * @return void
     */
    public function incrementLoginAttempts(array $input)
    {
        //
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param  array  $input
     *
     * @return void
     */
    public function clearLoginAttempts(array $input)
    {
        //
    }
}
