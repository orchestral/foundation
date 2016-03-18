<?php namespace Orchestra\Foundation\Traits;

use DateTime;
use Carbon\Carbon;

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
     * @return \Carbon\Carbon
     */
    public function toLocalTime($datetime)
    {
        $appTimeZone = $this->config->get('app.timezone', 'UTC');

        $datetime = $this->convertToDateTime($datetime, $appTimeZone);

        if ($this->auth->guest()) {
            return $datetime;
        }

        $userId       = $this->auth->user()->id;
        $userTimeZone = $this->memory->get("timezone.{$userId}", $appTimeZone);

        $datetime->timezone = $userTimeZone;

        return $datetime;
    }

    /**
     * Convert given time to user from localtime, however if it a guest user
     * return based on default timezone.
     *
     * @param  mixed  $datetime
     *
     * @return \Carbon\Carbon
     */
    public function fromLocalTime($datetime)
    {
        $appTimeZone = $this->config->get('app.timezone', 'UTC');

        if ($this->auth->guest()) {
            return $this->convertToDateTime($datetime, $appTimeZone);
        }

        $userId       = $this->auth->user()->id;
        $userTimeZone = $this->memory->get("timezone.{$userId}", $appTimeZone);
        $datetime     = $this->convertToDateTime($datetime, $userTimeZone);

        $datetime->timezone = $appTimeZone;

        return $datetime;
    }

    /**
     * Convert datetime string to DateTime.
     *
     * @param  mixed   $datetime
     * @param  string  $timezone
     *
     * @return \Carbon\Carbon
     */
    public function convertToDateTime($datetime, $timezone = null)
    {
        // Convert instanceof DateTime to Carbon
        if ($datetime instanceof DateTime) {
            $datetime = Carbon::instance($datetime);
        }

        if (! ($datetime instanceof Carbon)) {
            if (is_null($timezone)) {
                return new Carbon($datetime);
            }

            return new Carbon($datetime, $timezone);
        }

        ! is_null($timezone) && $datetime->timezone = $timezone;

        return $datetime;
    }
}
