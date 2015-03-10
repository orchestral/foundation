<?php namespace Orchestra\Foundation\Http\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Http\Filters\IsGuest;

class IsGuestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Filters\IsGuest::fiter()
     * method when is guest.
     *
     * @test
     */
    public function testFilterWhenIsGuest()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth       = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config     = m::mock('\Illuminate\Contracts\Config\Repository');

        $auth->shouldReceive('check')->once()->andReturn(true);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::routes.user')->andReturn('orchestra::/');
        $foundation->shouldReceive('handles')->once()->with('orchestra::/')->andReturn('http://localhost/admin');

        $stub = new IsGuest($foundation, $auth, $config);

        $this->assertInstanceOf('\Illuminate\Http\RedirectResponse', $stub->filter());
    }

    /**
     * Test Orchestra\Foundation\Filters\IsGuest::fiter()
     * method when is not guest.
     *
     * @test
     */
    public function testFilterWhenIsNotGuest()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth       = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config     = m::mock('\Illuminate\Contracts\Config\Repository');

        $auth->shouldReceive('check')->once()->andReturn(false);

        $stub = new IsGuest($foundation, $auth, $config);

        $this->assertNull($stub->filter());
    }
}
