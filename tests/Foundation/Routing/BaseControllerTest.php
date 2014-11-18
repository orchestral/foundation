<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Testing\TestCase;

class BaseControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $_SERVER['StubBaseController@setupFilters'] = false;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($_SERVER['StubBaseController@setupFilters']);

        m::close();
    }

    /**
     * Test Orchestra\Foundation\Routing\BaseController::missingMethod()
     * action.
     *
     * @test
     */
    public function testMissingMethodAction()
    {
        $app = new Container;
        $view = m::mock('\Illuminate\Contracts\View\Factory');
        $redirector = m::mock('\Illuminate\Routing\Redirector');

        $response = new \Illuminate\Routing\ResponseFactory($view, $redirector);
        $app['Illuminate\Contracts\Routing\ResponseFactory'] = $response;

        $view->shouldReceive('make')->once()
            ->with('orchestra/foundation::dashboard.missing', array())->andReturn('foo');

        Facade::setFacadeApplication($app);
        Container::setInstance($app);

        $this->assertFalse($_SERVER['StubBaseController@setupFilters']);

        $stub = new StubBaseController;
        $response = $stub->missingMethod(array());

        $this->assertTrue($_SERVER['StubBaseController@setupFilters']);

        $this->assertEquals('foo', $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }
}

class StubBaseController extends \Orchestra\Foundation\Routing\BaseController
{
    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $_SERVER['StubBaseController@setupFilters'] = true;
    }
}
