<?php

namespace Orchestra\Foundation\Auth\Throttle;

use Illuminate\Cache\RateLimiter;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as Command;

class Basic extends Throttle implements Command
{
    /**
     * The cache limiter implementation.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $cacheLimiter;

    /**
     * Construct a new processor.
     *
     * @param  \Illuminate\Cache\RateLimiter  $cacheLimiter
     */
    public function __construct(RateLimiter $cacheLimiter)
    {
        $this->cacheLimiter = $cacheLimiter;
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @return bool
     */
    public function hasTooManyLoginAttempts()
    {
        return $this->cacheLimiter->tooManyAttempts(
            $this->getUniqueLoginKey(),
            $this->maxLoginAttempts(),
            $this->lockoutTime() / 60
        );
    }

    /**
     * Determine how many retries left.
     *
     * @return int
     */
    public function retriesLeft()
    {
        return $this->cacheLimiter->retriesLeft(
            $this->getUniqueLoginKey(),
            $this->maxLoginAttempts()
        );
    }

    /**
     * Get total seconds before doing another login attempts for the user.
     *
     * @return int
     */
    public function getSecondsBeforeNextAttempts()
    {
        return (int) $this->cacheLimiter->availableIn($this->getUniqueLoginKey());
    }

    /**
     * Increment the login attempts for the user.
     *
     * @return void
     */
    public function incrementLoginAttempts()
    {
        $this->cacheLimiter->hit($this->getUniqueLoginKey());
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @return void
     */
    public function clearLoginAttempts()
    {
        $this->cacheLimiter->clear($this->getUniqueLoginKey());
    }
}
