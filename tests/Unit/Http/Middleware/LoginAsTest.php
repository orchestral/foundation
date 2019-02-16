<?php

namespace Orchestra\Tests\Unit\Http\Middleware;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Http\Middleware\LoginAs;

class LoginAsTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Middleware\LoginAs::handle()
     * method without redirection.
     *
     * @test
     */
    public function testHandleMethodWithoutRedirect()
    {
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $auth = m::mock('\Orchestra\Contracts\Auth\Guard');
        $request = m::mock('\Illuminate\Http\Request');
        $response = m::mock('\Illuminate\Contracts\Routing\ResponseFactory');

        $request->shouldReceive('input')->once()->with('_as')->andReturnNull();
        $acl->shouldReceive('canIf')->once()->with('manage orchestra')->andReturn(false);

        $next = function ($request) {
            return 'foo';
        };

        $stub = new LoginAs($acl, $auth, $response);

        $this->assertEquals('foo', $stub->handle($request, $next));
    }

    /**
     * Test Orchestra\Foundation\Middleware\LoginAs::handle()
     * method with redirection.
     *
     * @test
     */
    public function testHandleMethodWithRedirect()
    {
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $auth = m::mock('\Orchestra\Contracts\Auth\Guard');
        $request = m::mock('\Illuminate\Http\Request');
        $response = m::mock('\Illuminate\Contracts\Routing\ResponseFactory');
        $redirect = m::mock('\Illuminate\Http\RedirectResponse');

        $request->shouldReceive('input')->once()->with('_as')->andReturn(5)
            ->shouldReceive('url')->once()->andReturn('http://localhost');
        $acl->shouldReceive('canIf')->once()->with('manage orchestra')->andReturn(true);
        $auth->shouldReceive('loginUsingId')->once()->with(5)->andReturnNull();
        $response->shouldReceive('redirectTo')->once()->with('http://localhost')->andReturn($redirect);

        $next = function ($request) {
            return 'foo';
        };

        $stub = new LoginAs($acl, $auth, $response);

        $this->assertEquals($redirect, $stub->handle($request, $next));
    }
}
