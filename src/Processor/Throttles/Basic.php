<?php namespace Orchestra\Foundation\Processor\Throttles;

use Illuminate\Contracts\Cache\Repository;
use Orchestra\Contracts\Auth\Command\ThrottlesLogins;

class Basic extends Processor implements ThrottlesLogins
{
    /**
     * The cache repository implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Construct a new processor.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
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
        $attempts = $this->getLoginAttempts($input);

        $lockedOut = $this->cache->has($this->getLoginLockExpirationKey($input));

        if ($attempts > 5 || $lockedOut) {
            if (! $lockedOut) {
                $this->cache->put($this->getLoginLockExpirationKey($input), time() + 60, 1);
            }

            return true;
        }

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
        return $this->cache->get($this->getLoginAttemptsKey($input)) ?: 0;
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
        return (int) $this->cache->get($this->getLoginLockExpirationKey($input)) - time();
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
        $this->cache->add($key = $this->getLoginAttemptsKey($input), 1, 1);

        return (int) $this->cache->increment($key);
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
        $this->cache->forget($this->getLoginAttemptsKey($input));

        $this->cache->forget($this->getLoginLockExpirationKey($input));
    }
}
