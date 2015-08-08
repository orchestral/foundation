<?php namespace Orchestra\Foundation\Auth;

use Illuminate\Support\Arr;
use Illuminate\Cache\RateLimiter;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins as Command;

class BasicThrottle extends ThrottlesLogins implements Command
{
    /**
     * The cache limiter implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
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
     * @param  array  $input
     *
     * @return bool
     */
    public function hasTooManyLoginAttempts(array $input)
    {
        return $this->cacheLimiter->tooManyAttempts(
            $this->getLoginKey($input),
            $this->maxLoginAttempts(),
            $this->lockoutTime() / 60
        );
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
        return (int) $this->cacheLimiter->availableIn($this->getLoginKey($input));
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
        $this->cacheLimiter->hit($this->getLoginKey($input));
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
        $this->cacheLimiter->clear($this->getLoginKey($input));
    }
}
