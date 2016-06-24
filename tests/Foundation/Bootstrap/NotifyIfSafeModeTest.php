<?php

namespace Orchestra\Foundation\TestCase\Bootstrap;

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
    protected function setUp()
    {
        $this->app = new Application(__DIR__);

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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

        $app['orchestra.extension.status'] = $mode = m::mock('\Orchestra\Contracts\Extension\StatusChecker');
        $app['orchestra.messages'] = $messages = m::mock('\Orchestra\Contracts\Messages\MessageBag');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $messages->shouldReceive('extend')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($messages) {
                    return $c($messages);
                })
            ->shouldReceive('add')->once()->with('info', m::type('String'))->andReturnNull();

        $mode->shouldReceive('is')->once()->with('safe')->andReturn(true);

        (new NotifyIfSafeMode())->bootstrap($app);
    }
}
