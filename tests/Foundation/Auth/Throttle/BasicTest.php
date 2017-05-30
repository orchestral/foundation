<?php

namespace Orchestra\Foundation\TestCase\Auth\Throttle;

use Mockery as m;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Auth\Throttle\Basic as BasicThrottle;

class BasicTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    public function testHasTooManyLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $request = Request::create('/', 'GET', [
            'username' => $key = 'admin@orchestraplatform.com',
        ], [], [], ['REMOTE_ADDR' => $ip = '127.0.0.1']);

        $stub = new BasicThrottle($cache);
        $stub->setRequest($request);

        $cache->shouldReceive('tooManyAttempts')->once()->with($key.$ip, 5, 1)->andReturn(false);

        $this->assertFalse($stub->hasTooManyLoginAttempts());
    }

    public function testHasTooManyLoginAttemptsMethodWhenLocked()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $request = Request::create('/', 'GET', [
            'username' => $key = 'admin@orchestraplatform.com',
        ], [], [], ['REMOTE_ADDR' => $ip = '127.0.0.1']);

        $stub = new BasicThrottle($cache);
        $stub->setRequest($request);

        $cache->shouldReceive('tooManyAttempts')->once()->with($key.$ip, 5, 1)->andReturn(true);

        $this->assertTrue($stub->hasTooManyLoginAttempts());
    }

    public function testGetSecondsBeforeNextAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $request = Request::create('/', 'GET', [
            'username' => $key = 'admin@orchestraplatform.com',
        ], [], [], ['REMOTE_ADDR' => $ip = '127.0.0.1']);

        $stub = new BasicThrottle($cache);
        $stub->setRequest($request);

        $cache->shouldReceive('availableIn')->once()->with($key.$ip)->andReturn(50);

        $this->assertEquals(50, $stub->getSecondsBeforeNextAttempts());
    }

    public function testIncrementLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $request = Request::create('/', 'GET', [
            'username' => $key = 'admin@orchestraplatform.com',
        ], [], [], ['REMOTE_ADDR' => $ip = '127.0.0.1']);

        $stub = new BasicThrottle($cache);
        $stub->setRequest($request);

        $cache->shouldReceive('hit')->once()->with($key.$ip)->andReturnNull();

        $this->assertNull($stub->incrementLoginAttempts());
    }

    public function testClearLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Cache\RateLimiter');
        $request = Request::create('/', 'GET', [
            'username' => $key = 'admin@orchestraplatform.com',
        ], [], [], ['REMOTE_ADDR' => $ip = '127.0.0.1']);

        $limit = time() + 60;

        $stub = new BasicThrottle($cache);
        $stub->setRequest($request);

        $cache->shouldReceive('clear')->once()->with($key.$ip)->andReturnNull();

        $this->assertNull($stub->clearLoginAttempts());
    }
}
