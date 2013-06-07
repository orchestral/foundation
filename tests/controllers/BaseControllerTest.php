<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;

class BaseControllerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Routing\BaseController::missingMethod() action.
	 */
	public function testMissingMethodAction()
	{
		\Illuminate\Support\Facades\Facade::setFacadeApplication($app = new \Illuminate\Container\Container);

		$app['view'] = $view = m::mock('View');

		$view->shouldReceive('make')->once()->with('orchestra/foundation::dashboard.missing', array())->andReturn('foo');

		$response = with(new StubBaseController)->missingMethod(array());

		$this->assertEquals('foo', $response->getContent());
		$this->assertEquals(404, $response->getStatusCode());
	}
}

class StubBaseController extends \Orchestra\Routing\BaseController {}
