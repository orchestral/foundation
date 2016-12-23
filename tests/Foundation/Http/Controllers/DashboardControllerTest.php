<?php

namespace Orchestra\Foundation\TestCase\Http\Controllers;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DashboardControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Test GET /admin.
     *
     * @test
     */
    public function testIndexAction()
    {
        $this->getProcessorMock()->shouldReceive('show')->once()
            ->andReturnUsing(function ($listener) {
                return $listener->showDashboard(['panes' => 'foo']);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::dashboard.index', ['panes' => 'foo'], [])->andReturn('foo');

        $this->call('GET', 'admin');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/missing.
     *
     * @test
     */
    public function testMissingAction()
    {
        $this->call('GET', 'admin/missing');
        $this->assertResponseStatus(404);
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\Account\ProfileDashboard
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\Account\ProfileDashboard', [
            m::mock('\Orchestra\Widget\WidgetManager'),
        ]);

        $this->app->instance('Orchestra\Foundation\Processor\Account\ProfileDashboard', $processor);

        return $processor;
    }
}
