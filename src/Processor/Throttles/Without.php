<?php namespace Orchestra\Foundation\Processor\Throttles;

use Orchestra\Contracts\Auth\Command\ThrottlesLogins;

class Without extends Processor implements ThrottlesLogins
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
     * Get the login attempts for the user.
     *
     * @param  array  $input
     *
     * @return int
     */
    public function getLoginAttempts(array $input)
    {
        return 0;
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
        return 60;
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  array  $input
     *
     * @return int
     */
    public function incrementLoginAttempts(array $input)
    {
        return 1;
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
