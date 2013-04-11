<?php namespace Orchestra\Foundation\Tests;

class InstallerTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	public function testInstalledMethod()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$app     = new \Illuminate\Foundation\Application($request);
                
		$app['orchestra.installed'] = false;

		$stub = new \Orchestra\Foundation\Installer($app);

		$this->assertFalse($stub->installed());

		$app['orchestra.installed'] = true;

		$stub = new \Orchestra\Foundation\Installer($app);

		$this->assertTrue($stub->installed());
	}
}