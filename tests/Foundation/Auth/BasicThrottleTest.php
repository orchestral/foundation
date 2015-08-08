<?php namespace Orchestra\Foundation\Auth\TestCase;

use Mockery as m;
use Orchestra\Foundation\Auth\BasicThrottle;

class BasicThrottleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    public function testHasTooManyLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = implode('', $input);

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('tooManyAttempts')->once()->with($key, 5, 1)->andReturn(false);

        $this->assertFalse($stub->hasTooManyLoginAttempts($input));
    }

    public function testHasTooManyLoginAttemptsMethodWhenLocked()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = implode('', $input);

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('tooManyAttempts')->once()->with($key, 5, 1)->andReturn(true);

        $this->assertTrue($stub->hasTooManyLoginAttempts($input));
    }

    public function testGetSecondsBeforeNextAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = implode('', $input);

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('availableIn')->once()->with($key)->andReturn(50);

        $this->assertEquals(50, $stub->getSecondsBeforeNextAttempts($input));
    }

    public function testIncrementLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = implode('', $input);

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('hit')->once()->with($key)->andReturnNull();

        $this->assertNull($stub->incrementLoginAttempts($input));
    }

    public function testClearLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key   = implode('', $input);
        $limit = time() + 60;

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('clear')->once()
                ->with($key)->andReturnNull();

        $this->assertNull($stub->clearLoginAttempts($input));
    }
}
