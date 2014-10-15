<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\Widget;

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
        m::close();
    }

    /**
     * Test GET /admin
     *
     * @test
     */
    public function testIndexAction()
    {
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::dashboard.index', array('panes' => array()))
            ->andReturn('foo');
        Widget::shouldReceive('make')->once()->with('pane.orchestra')->andReturn(array());

        $this->call('GET', 'admin');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/missing
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testMissingAction()
    {
        $this->call('GET', 'admin/missing');
    }
}
