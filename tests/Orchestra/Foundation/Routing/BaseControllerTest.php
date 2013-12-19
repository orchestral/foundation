<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;

class BaseControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $_SERVER['StubBaseController@setupFilters'] = false;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($_SERVER['StubBaseController@setupFilters']);
        Facade::setFacadeApplication(null);
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
        $app = array(
            'view' => $view = m::mock('\Illuminate\View\Environment'),
        );

        $view->shouldReceive('make')->once()
            ->with('orchestra/foundation::dashboard.missing', array())->andReturn('foo');

        Facade::setFacadeApplication($app);

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
