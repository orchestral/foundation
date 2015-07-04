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
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = md5(implode('', $input));

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('get')->once()
                ->with("login:attempts:{$key}")
                ->andReturn(1)
            ->shouldReceive('has')->once()
                ->with("login:expiration:{$key}")
                ->andReturn(false);

        $this->assertFalse($stub->hasTooManyLoginAttempts($input));
    }

    public function testHasTooManyLoginAttemptsMethodWhenLocked()
    {
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = md5(implode('', $input));

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('get')->once()
                ->with("login:attempts:{$key}")
                ->andReturn(10)
            ->shouldReceive('has')->once()
                ->with("login:expiration:{$key}")
                ->andReturn(false)
            ->shouldReceive('put')->once()
                ->with("login:expiration:{$key}", m::type('int'), 1)
                ->andReturnNull();

        $this->assertTrue($stub->hasTooManyLoginAttempts($input));
    }

    public function testGetLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = md5(implode('', $input));

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('get')->once()
                ->with("login:attempts:{$key}")
                ->andReturn(1);

        $this->assertEquals(1, $stub->getLoginAttempts($input));
    }

    public function testGetSecondsBeforeNextAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key   = md5(implode('', $input));
        $limit = time() + 60;

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('get')->once()
                ->with("login:expiration:{$key}")
                ->andReturn($limit);

        $this->assertLessThan($limit, $stub->getSecondsBeforeNextAttempts($input));
    }

    public function testIncrementLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key = md5(implode('', $input));

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('add')->once()
                ->with("login:attempts:{$key}", 1, 1)
                ->andReturnNull()
            ->shouldReceive('increment')->once()
                ->with("login:attempts:{$key}")
                ->andReturn(1);

        $this->assertEquals(1, $stub->incrementLoginAttempts($input));
    }

    public function testClearLoginAttemptsMethod()
    {
        $cache = m::mock('\Illuminate\Contracts\Cache\Repository');
        $input = [
            'email' => 'admin@orchestraplatform.com',
            '_ip'   => '127.0.0.1'
        ];

        $key   = md5(implode('', $input));
        $limit = time() + 60;

        $stub = new BasicThrottle($cache);

        $cache->shouldReceive('forget')->once()
                ->with("login:attempts:{$key}")
                ->andReturn($limit)
            ->shouldReceive('forget')->once()
                ->with("login:expiration:{$key}")
                ->andReturn($limit);

        $this->assertNull($stub->clearLoginAttempts($input));
    }
}
