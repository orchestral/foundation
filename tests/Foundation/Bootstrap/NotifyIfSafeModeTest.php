<?php namespace Orchestra\Foundation\Bootstrap\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\Application;
use Orchestra\Foundation\Bootstrap\NotifyIfSafeMode;

class NotifyIfSafeModeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    private $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Application(__DIR__);

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);
    }
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        Facade::clearResolvedInstances();

        m::close();
    }

    /**
     * Test Orchestra\Foundation\Bootstrap\NotifyIfSafeMode::bootstrap()
     * method.
     *
     * @test
     */
    public function testBootstrapMethod()
    {
        $app = $this->app;

        $app['events'] = $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $app['session'] = $session = m::mock('\Illuminate\Contracts\Session\SessionInterface');
        $app['orchestra.messages'] = $messages = m::mock('\Orchestra\Messages\MessageBag');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $events->shouldReceive('listen')->once()->with('orchestra.extension: booted', m::type('Closure'))
            ->andReturnUsing(function ($n, $c) {
                return $c();
            });

        $messages->shouldReceive('extend')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($messages) {
                    return $c($messages);
                })
            ->shouldReceive('add')->once()->with('info', m::type('String'))->andReturnNull();

        $session->shouldReceive('get')->once()->with('orchestra.safemode')->andReturn('on');

        (new NotifyIfSafeMode)->bootstrap($app);
    }
}
