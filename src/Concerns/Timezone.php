<?php

namespace Orchestra\Foundation\Concerns;

use DateTime;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Auth\Authenticatable;

trait Timezone
{
    /**
     * Auth instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Memory instance.
     *
     * @var \Orchestra\Contracts\Memory\Provider
     */
    protected $memory;

    /**
     * Convert given time to user localtime, however if it a guest user
     * return based on default timezone.
     *
     * @param  mixed  $datetime
     *
     * @return \Carbon\CarbonInter
     */
    public function toLocalTime($datetime): CarbonInterface
    {
        $user = \resolve(Authenticatable::class);

        $datetime = \carbonize(
            $datetime, $appTimeZone = \config('app.timezone', 'UTC')
        );

        if (\is_null($user)) {
            return $datetime;
        }

        return \use_timezone(
            $datetime, \memorize("timezone.{$user->id}", $appTimeZone)
        );
    }

    /**
     * Convert given time to user from localtime, however if it a guest user
     * return based on default timezone.
     *
     * @param  mixed  $datetime
     *
     * @return \Carbon\CarbonInterface
     */
    public function fromLocalTime($datetime): CarbonInterface
    {
        $user = \resolve(Authenticatable::class);

        $appTimeZone = \config('app.timezone', 'UTC');

        if (\is_null($user)) {
            return \carbonize($datetime, $appTimeZone);
        }

        $datetime = \carbonize(
            $datetime, \memorize("timezone.{$user->id}", $appTimeZone)
        );

        return \use_timezone($datetime, $appTimeZone);
    }
}
