<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;

class BaseControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Routing\BaseController::missingMethod() action.
	 *
	 * @test
	 */
	public function testMissingMethodAction()
	{
		View::shouldReceive('make')->once()
			->with('orchestra/foundation::dashboard.missing', array())->andReturn('foo');

		$response = with(new StubBaseController)->missingMethod(array());

		$this->assertEquals('foo', $response->getContent());
		$this->assertEquals(404, $response->getStatusCode());
	}
}

class StubBaseController extends \Orchestra\Foundation\Routing\BaseController {}
