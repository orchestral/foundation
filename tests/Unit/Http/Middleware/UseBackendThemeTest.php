<?php

namespace Orchestra\Tests\Unit\Http\Middleware;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Http\Middleware\UseBackendTheme;

class UseBackendThemeTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Middleware\UseBackendTheme::handle()
     * method.
     *
     * @test
     */
    public function testHandleMethod()
    {
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $request = m::mock('\Illuminate\Http\Request');

        $events->shouldReceive('dispatch')->once()->with('orchestra.started: admin')->andReturnNull()
            ->shouldReceive('dispatch')->once()->with('orchestra.ready: admin')->andReturnNull()
            ->shouldReceive('dispatch')->once()->with('orchestra.done: admin')->andReturnNull();

        $next = function ($request) {
            return 'foo';
        };

        $stub = new UseBackendTheme($events);

        $this->assertEquals('foo', $stub->handle($request, $next));
    }
}
