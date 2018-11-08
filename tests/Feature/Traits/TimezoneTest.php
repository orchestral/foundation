<?php

namespace Orchestra\Tests\Feature\Traits;

use Mockery as m;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Traits\Timezone;
use Orchestra\Foundation\Testing\Installation;

class TimezoneTest extends TestCase
{
    use Installation,
        Timezone;

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    /**
     * @test
     * @group support
     */
    public function it_can_convert_to_local_time_when_given_string_as_date()
    {
        $this->assertGuest();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = $this->app->make('orchestra.memory')->driver('user');

        $stub = $this->toLocalTime('2012-01-01 00:00:00');

        $this->assertEquals(new CarbonTimeZone('UTC'), $stub->getTimezone());
    }

    /**
     * Test Orchestra\Foundation\Traits\Timezone::toLocalTime() method return proper
     * datetime when is guest.
     *
     * @test
     * @group support
     */
    public function it_can_convert_to_local_time_for_guest()
    {
        $this->assertGuest();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = $this->app->make('orchestra.memory')->driver('user');

        $stub = $this->toLocalTime(new Carbon('2012-01-01 00:00:00'));

        $this->assertEquals(new CarbonTimeZone('UTC'), $stub->getTimezone());
    }

    /**
     * Test Orchestra\Foundation\Traits\Timezone::toLocalTime() method return proper
     * datetime when is user.
     *
     * @test
     * @group support
     */
    public function it_can_convert_to_local_time_for_user()
    {
        $this->be($user = $this->createUserAsMember());

        $this->assertAuthenticated();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $this->memory->shouldReceive('get')->once()
            ->with("timezone.{$user->id}", 'UTC')->andReturn('Asia/Kuala_Lumpur');

        $stub = $this->toLocalTime(new Carbon('2012-01-01 00:00:00'));

        $this->assertEquals(new CarbonTimeZone('Asia/Kuala_Lumpur'), $stub->timezone);
        $this->assertEquals('2012-01-01 08:00:00', $stub->toDateTimeString());
    }

    /**
     * @test
     * @group support
     */
    public function it_can_convert_from_local_time_for_guest()
    {
        $this->assertGuest();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = $this->app->make('orchestra.memory')->driver('user');

        $stub = $this->fromLocalTime('2012-01-01 00:00:00');

        $this->assertEquals(new CarbonTimeZone('UTC'), $stub->timezone);
        $this->assertEquals('2012-01-01 00:00:00', $stub->toDateTimeString());
    }

    /**
     * @test
     * @group support
     */
    public function it_can_convert_from_local_time_for_user()
    {
        $this->be($user = $this->createUserAsMember());

        $this->assertAuthenticated();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $this->memory->shouldReceive('get')->once()
            ->with("timezone.{$user->id}", 'UTC')->andReturn('Asia/Kuala_Lumpur');

        $stub = $this->fromLocalTime('2012-01-01 08:00:00');

        $this->assertEquals(new CarbonTimeZone('UTC'), $stub->timezone);
        $this->assertEquals('2012-01-01 00:00:00', $stub->toDateTimeString());
    }

    /**
     * @test
     * @group support
     */
    public function it_can_convert_to_datetime_when_timezone_is_null()
    {
        $this->assertGuest();

        $this->config = $this->app['config'];
        $this->auth = $this->app['auth'];
        $this->memory = $this->app->make('orchestra.memory')->driver('user');

        $stub = $this->convertToDateTime('2012-01-01 08:00:00');

        $this->assertInstanceOf('\Carbon\Carbon', $stub);
        $this->assertEquals('2012-01-01 08:00:00', $stub->toDateTimeString());
    }
}
