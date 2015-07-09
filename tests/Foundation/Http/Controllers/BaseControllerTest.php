<?php namespace Orchestra\Foundation\Http\Controllers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Orchestra\Foundation\Http\Controllers\BaseController;

class BaseControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();

        $_SERVER['StubBaseController@setupFilters'] = false;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($_SERVER['StubBaseController@setupFilters']);
    }

    /**
     * Test Orchestra\Foundation\Http\Controllers\BaseController::missingMethod()
     * action.
     *
     * @test
     */
    public function testMissingMethodAction()
    {
        $app        = new Container();
        $factory    = m::mock('\Illuminate\Contracts\View\Factory');
        $view       = m::mock('\Illuminate\Contracts\View\View');
        $redirector = m::mock('\Illuminate\Routing\Redirector');
        $response   = m::mock('\Illuminate\Routing\ResponseFactory', [$factory, $redirector]);

        $app['Illuminate\Contracts\Routing\ResponseFactory'] = $response;

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
        Container::setInstance($app);

        $response->shouldReceive('view')->once()
            ->with('orchestra/foundation::dashboard.missing', [], 404)->andReturn($view);

        $this->assertFalse($_SERVER['StubBaseController@setupFilters']);

        $stub = new StubBaseController();

        $this->assertEquals($view, $stub->missingMethod([]));
        $this->assertTrue($_SERVER['StubBaseController@setupFilters']);
    }
}

class StubBaseController extends BaseController
{
    protected function setupMiddleware()
    {
        $_SERVER['StubBaseController@setupFilters'] = true;
    }
}
