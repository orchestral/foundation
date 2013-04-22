<?php namespace Orchestra\Foundation\Tests;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$this->app = new \Illuminate\Foundation\Application($request);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Installer::installed() method.
	 *
	 * @test
	 */
	public function testInstalledMethod()
	{
		$this->app['orchestra.installed'] = false;

		$stub = new \Orchestra\Foundation\Application($this->app);

		$this->assertFalse($stub->installed());

		$this->app['orchestra.installed'] = true;

		$this->assertTrue($stub->installed());
	}
}