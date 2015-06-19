<?php namespace Orchestra\Foundation\Providers\TestCase;

use Mockery as m;
use Orchestra\Foundation\Providers\FoundationServiceProvider;

class FoundationServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test event are registered.
     *
     * @test
     */
    public function testRegisterEventsOnAfter()
    {
        $app = m::mock('\Orchestra\Foundation\Application[terminating]');
        $app['events'] = $events = m::mock('\Illuminate\Contracts\Events\Dispatcher[fire]');
        $app['router'] = $router = m::mock('\Illuminate\Routing\Router');
        $events->shouldReceive('fire')->once()->with('orchestra.done')->andReturnNull();

        $app->shouldReceive('terminating')->once()->with(m::type('Closure'))
            ->andReturnUsing(function ($c) {
                $c();
            });

        $foundation = new FoundationServiceProvider($app);
        $foundation->register();
    }
}
