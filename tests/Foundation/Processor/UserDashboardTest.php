<?php namespace Orchestra\Foundation\Processor\TestCase;

use Mockery as m;
use Orchestra\Foundation\Processor\Account\ProfileDashboard;

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
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard');
        $widget = m::mock('\Orchestra\Widget\WidgetManager');

        $stub = new ProfileDashboard($widget);

        $widget->shouldReceive('make')->once()->with('pane.orchestra')->andReturn([]);

        $listener->shouldReceive('showDashboard')->once()->with(['panes' => []])->andReturn('show.dashboard');

        $this->assertEquals('show.dashboard', $stub->show($listener));
    }
}
