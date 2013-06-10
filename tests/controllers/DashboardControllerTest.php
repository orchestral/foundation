<?php namespace Orchestra\Foundation\Tests\Routing;

use Orchestra\Services\TestCase;

class DashboardControllerTest extends TestCase {

	/**
	 * Test GET /admin
	 * 
	 * @test
	 */
	public function testIndexAction()
	{
		$this->call('GET', 'admin');
		$this->assertResponseOk();
		$this->assertViewHas('panes');
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
