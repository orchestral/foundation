<?php

namespace Orchestra\Foundation\Auth\Throttle;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;

abstract class Throttle
{
    /**
     * The configurations.
     *
     * @var array
     */
    protected static $config = [
        'attempts' => 5,
        'locked_for' => 60,
    ];

    /**
     * Login key name.
     *
     * @var string
     */
    protected $loginKey = 'username';

    /**
     * The HTTP Request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

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
     * Set HTTP Request object.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set user login key.
     *
     * @param  string  $key
     *
     * @return $this
     */
    public function setLoginKey($key)
    {
        $this->loginKey = $key;

        return $this;
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @return void
     */
    public function fireLockoutEvent()
    {
        Event::dispatch(new Lockout($this->request));
    }

    /**
     * Get the maximum number of login attempts for delaying further attempts.
     *
     * @return int
     */
    protected function maxLoginAttempts()
    {
        return static::$config['attempts'] ?? 5;
    }

    /**
     * The number of seconds to delay further login attempts.
     *
     * @return int
     */
    protected function lockoutTime()
    {
        return static::$config['locked_for'] ?? 60;
    }

    /**
     * Get the login key.
     *
     * @return string
     */
    protected function getUniqueLoginKey()
    {
        $key = $this->request->input($this->loginKey);
        $ip = $this->request->ip();

        return mb_strtolower($key).$ip;
    }
}
