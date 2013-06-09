<?php namespace Orchestra\Foundation\Tests\Routing;

use Orchestra\Services\TestCase;

class DashboardControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function testIndexAction()
	{
		$this->call('GET', 'admin');
		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function testMissingAction()
	{
		$this->call('GET', 'admin/missing');
		$this->assertResponseStatus(404);
	}
}
