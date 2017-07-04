<?php

namespace Orchestra\Foundation\Auth\Throttle;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as Command;

class Without extends Throttle implements Command
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @return bool
     */
    public function hasTooManyLoginAttempts()
    {
        return false;
    }

    /**
     * Determine how many retries left.
     *
     * @return int
     */
    public function retriesLeft()
    {
        return $this->maxLoginAttempts();
    }

    /**
     * Get total seconds before doing another login attempts for the user.
     *
     * @return int
     */
    public function getSecondsBeforeNextAttempts()
    {
        return static::$config['locked_for'] ?? 60;
    }

    /**
     * Increment the login attempts for the user.
     *
     * @return void
     */
    public function incrementLoginAttempts()
    {
        //
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @return void
     */
    public function clearLoginAttempts()
    {
        //
    }
}
