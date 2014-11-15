<?php namespace Orchestra\Foundation\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Filters\Authenticate;

class AuthenticateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Filters\Authenticated::filter()
     * method when request is ajax.
     *
     * @test
     */
    public function testFilterMethodWhenAjaxRequest()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');
        $response = m::mock('\Illuminate\Contracts\Routing\ResponseFactory');

        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $auth->shouldReceive('guest')->once()->andReturn(true);
        $request->shouldReceive('ajax')->once()->andReturn(true);
        $response->shouldReceive('make')->once()->with('Unauthorized', 401)->andReturn('foo');

        $stub = new Authenticate($foundation, $auth, $config, $response);
        $this->assertEquals('foo', $stub->filter($route, $request));
    }

    /**
     * Test Orchestra\Foundation\Filters\Authenticated::filter()
     * method when request is html.
     *
     * @test
     */
    public function testFilterMethodWhenHtmlRequest()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');
        $response = m::mock('\Illuminate\Contracts\Routing\ResponseFactory');

        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $auth->shouldReceive('guest')->once()->andReturn(true);
        $request->shouldReceive('ajax')->once()->andReturn(false);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::routes.guest')->andReturn('orchestra::login');
        $foundation->shouldReceive('handles')->once()->with('orchestra::login')->andReturn('http://localhost/admin/login');
        $response->shouldReceive('redirectGuest')->once()->with('http://localhost/admin/login')->andReturn('foo');

        $stub = new Authenticate($foundation, $auth, $config, $response);
        $this->assertEquals('foo', $stub->filter($route, $request));
    }

    /**
     * Test Orchestra\Foundation\Filters\Authenticated::filter()
     * method os not guest.
     *
     * @test
     */
    public function testFilterMethodIsNotGuest()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config = m::mock('\Illuminate\Contracts\Config\Repository');
        $response = m::mock('\Illuminate\Contracts\Routing\ResponseFactory');

        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $auth->shouldReceive('guest')->once()->andReturn(false);

        $stub = new Authenticate($foundation, $auth, $config, $response);
        $this->assertNull($stub->filter($route, $request));
    }
}
