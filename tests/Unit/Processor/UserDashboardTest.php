<?php

namespace Orchestra\Tests\Unit\Processor;

use Mockery as m;
use Orchestra\Foundation\Processors\Account\ProfileDashboard;
use PHPUnit\Framework\TestCase;

class UserDashboardTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_show_dashboard()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard');
        $widget = m::mock('\Orchestra\Widget\WidgetManager');

        $stub = new ProfileDashboard($widget);

        $widget->shouldReceive('make')->once()->with('pane.orchestra')->andReturn([]);

        $listener->shouldReceive('showDashboard')->once()->with(['panes' => []])->andReturn('show.dashboard');

        $this->assertEquals('show.dashboard', $stub->show($listener));
    }
}
