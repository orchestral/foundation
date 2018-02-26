<?php

namespace Orchestra\Tests\Unit\Http\Controllers;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Orchestra\Foundation\Http\Controllers\BaseController;

class BaseControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();

        $_SERVER['StubBaseController@setupFilters'] = false;
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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
        $app = new Container();
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

        $stub = new class() extends BaseController {
            protected function onCreate()
            {
                $_SERVER['StubBaseController@setupFilters'] = true;
            }
        };

        $this->assertEquals($view, $stub->missingMethod([]));
        $this->assertTrue($_SERVER['StubBaseController@setupFilters']);
    }
}
