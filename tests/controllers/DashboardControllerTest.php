<?php namespace Orchestra\Foundation\Tests\Routing;

use \Orchestra\Services\TestCase;

class DashboardControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function testIndexAction()
	{
		$this->action('GET', 'Orchestra\Routing\DashboardController@index');
		$this->assertResponseOk();
		$this->assertFalse(false);
	}
}
