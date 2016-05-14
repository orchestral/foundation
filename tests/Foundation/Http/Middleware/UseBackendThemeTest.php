<?php namespace Orchestra\Foundation\Http\Middleware\TestCase;

use Mockery as m;
use Orchestra\Foundation\Http\Middleware\UseBackendTheme;

class UseBackendThemeTest extends \PHPUnit_Framework_TestCase
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

        $events->shouldReceive('fire')->once()->with('orchestra.started: admin')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.ready: admin')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.done: admin')->andReturnNull();

        $next = function ($request) {
            return 'foo';
        };

        $stub = new UseBackendTheme($events);

        $this->assertEquals('foo', $stub->handle($request, $next));
    }
}
