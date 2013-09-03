<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\Widget;

class DashboardControllerTest extends TestCase {

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
			->with('orchestra/foundation::dashboard.index')->andReturn(m::self());
		View::shouldReceive('with')->once()->with('panes', array())->andReturn('foo');
		Widget::shouldReceive('make')->once()->with('pane.orchestra')->andReturn(array());

		$this->call('GET', 'admin');
		$this->assertResponseOk();
	}

	/**
	 * Test GET /admin/missing
	 * 
	 * @test
	 */
	public function testMissingAction()
	{
		$this->call('GET', 'admin/missing');
		$this->assertResponseStatus(404);
	}
}
