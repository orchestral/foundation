<?php namespace Orchestra\Foundation\Filters\TestCase;

use Mockery as m;
use Orchestra\Foundation\Filters\VerifyCsrfToken;

class VerifyCsrfTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method with invalid csrf token.
     *
     * @expectedException \Illuminate\Session\TokenMismatchException
     */
    public function testFilterMethodWithInvalidToken()
    {
        $encrypter = m::mock('\Illuminate\Contracts\Encryption\Encrypter');
        $session = m::mock('\Illuminate\Session\SessionInterface');
        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('session')->once()->andReturn($session)
            ->shouldReceive('header')->once()->with('X-XSRF-TOKEN')->andReturnNull()
            ->shouldReceive('input')->once()->with('_token')->andReturn('b');
        $session->shouldReceive('token')->once()->andReturn('a');

        $stub = new VerifyCsrfToken($encrypter);

        $stub->filter($route, $request);
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method with valid csrf token.
     *
     * @test
     */
    public function testFilterMethodWithValidToken()
    {
        $encrypter = m::mock('\Illuminate\Contracts\Encryption\Encrypter');
        $session = m::mock('\Illuminate\Session\SessionInterface');
        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('session')->once()->andReturn($session)
            ->shouldReceive('header')->once()->with('X-XSRF-TOKEN')->andReturnNull()
            ->shouldReceive('input')->once()->with('_token')->andReturn('a');
        $session->shouldReceive('token')->once()->andReturn('a');

        $stub = new VerifyCsrfToken($encrypter);

        $this->assertNull($stub->filter($route, $request));
    }

    /**
     * Test Orchestra\Foundation\Filters\CanBeInstalled::filter()
     * method with valid csrf token using header.
     *
     * @test
     */
    public function testFilterMethodWithValidTokenUsingHeaders()
    {
        $encrypter = m::mock('\Illuminate\Contracts\Encryption\Encrypter');
        $session = m::mock('\Illuminate\Session\SessionInterface');
        $route = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('session')->once()->andReturn($session)
            ->shouldReceive('header')->once()->with('X-XSRF-TOKEN')->andReturn('foobar')
            ->shouldReceive('input')->once()->with('_token')->andReturnNull();
        $encrypter->shouldReceive('decrypt')->once()->with('foobar')->andReturn('a');
        $session->shouldReceive('token')->once()->andReturn('a');

        $stub = new VerifyCsrfToken($encrypter);

        $this->assertNull($stub->filter($route, $request));
    }
}
