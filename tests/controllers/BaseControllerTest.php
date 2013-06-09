<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

class BaseControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Routing\BaseController::missingMethod() action.
	 *
	 * @test
	 */
	public function testMissingMethodAction()
	{
		
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::dashboard.missing', array())->andReturn('foo');

		$response = with(new StubBaseController)->missingMethod(array());

		$this->assertEquals('foo', $response->getContent());
		$this->assertEquals(404, $response->getStatusCode());
	}
}

class StubBaseController extends \Orchestra\Routing\BaseController {}
