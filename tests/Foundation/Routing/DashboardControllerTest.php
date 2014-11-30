<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->once()->with('errors', m::any());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test GET /admin
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
     * Test GET /admin/missing
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testMissingAction()
    {
        $this->call('GET', 'admin/missing');
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
