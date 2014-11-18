<?php namespace Orchestra\Foundation\Processor\TestCase;

use Mockery as m;
use Orchestra\Foundation\Processor\UserDashboard;

class UserDashboardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\UserDashboard::show()
     * method.
     *
     * @test
     */
    public function testShowMethod()
    {
        $listener = m::mock('\Orchestra\Foundation\Contracts\Listener\UserDashboard');
        $widget = m::mock('\Orchestra\Widget\WidgetManager');

        $stub = new UserDashboard($widget);

        $widget->shouldReceive('make')->once()->with('pane.orchestra')->andReturn([]);

        $listener->shouldReceive('showDashboard')->once()->with(['panes' => []])->andReturn('show.dashboard');

        $this->assertEquals('show.dashboard', $stub->show($listener));
    }
}
