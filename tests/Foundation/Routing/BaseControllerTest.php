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
        $factory = m::mock('\Illuminate\Contracts\View\Factory');
        $view = m::mock('\Illuminate\Contracts\View\View');
        $redirector = m::mock('\Illuminate\Routing\Redirector');
        $response = m::mock('\Illuminate\Routing\ResponseFactory', [$factory, $redirector]);

        $app['Illuminate\Contracts\Routing\ResponseFactory'] = $response;

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
        Container::setInstance($app);

        $response->shouldReceive('view')->once()
            ->with('orchestra/foundation::dashboard.missing', [], 404)->andReturn($view);

        $this->assertFalse($_SERVER['StubBaseController@setupFilters']);

        $stub = new StubBaseController;

        $this->assertEquals($view, $stub->missingMethod([]));
        $this->assertTrue($_SERVER['StubBaseController@setupFilters']);
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
