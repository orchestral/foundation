<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

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
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::dashboard.index')->andReturn(m::self());
		\Illuminate\Support\Facades\View::shouldReceive('with')->once()
			->with('panes', 'panes-widget')->andReturn('foo');
		\Orchestra\Support\Facades\Widget::shouldReceive('make')->once()
			->with('pane.orchestra')->andReturn('panes-widget');

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
