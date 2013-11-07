<?php namespace Orchestra\Foundation;

use DateTime;
use Carbon\Carbon;

class Site extends Relic
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app = null;

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Foundation\Application   $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Convert given time to user localtime, however if it a guest user
     * return based on default timezone.
     *
     * @param  mixed    $datetime
     * @return \Carbon\Carbon
     */
    public function toLocalTime($datetime)
    {
        $app         = $this->app;
        $appTimeZone = $app['config']->get('app.timezone', 'UTC');

        if (! ($datetime instanceof DateTime)) {
            $datetime = $this->convertToDateTime($datetime, $appTimeZone);
        }

        if ($app['auth']->guest()) {
            return $datetime;
        }

        $userId       = $app['auth']->user()->id;
        $userMeta     = $app['orchestra.memory']->make('user');
        $userTimeZone = $userMeta->get("timezone.{$userId}", $appTimeZone);

        $datetime->timezone = $userTimeZone;

        return $datetime;
    }

    /**
     * Convert given time to user from localtime, however if it a guest user
     * return based on default timezone.
     *
     * @param  mixed    $datetime
     * @return \Carbon\Carbon
     */
    public function fromLocalTime($datetime)
    {
        $app          = $this->app;
        $appTimeZone  = $app['config']->get('app.timezone', 'UTC');

        if ($app['auth']->guest()) {
            return $this->convertToDateTime($datetime, $appTimeZone);
        }

        $userId       = $app['auth']->user()->id;
        $userMeta     = $app['orchestra.memory']->make('user');
        $userTimeZone = $userMeta->get("timezone.{$userId}", $appTimeZone);
        $datetime     = $this->convertToDateTime($datetime, $userTimeZone);

        $datetime->timezone = $appTimeZone;

        return $datetime;
    }

    /**
     * Convert datetime string to DateTime.
     *
     * @param  mixed    $datetime
     * @param  string   $timezone
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

        ! is_null($timezone) and $datetime->timezone = $timezone;

        return $datetime;
    }
}
