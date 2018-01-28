<?php

namespace Orchestra\Tests\Unit\Processor;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Processor\Account\ProfileDashboard;

class UserDashboardTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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
