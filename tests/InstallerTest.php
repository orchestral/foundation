<?php namespace Orchestra\Foundation\Tests;

class InstallerTest extends \PHPUnit_Framework_TestCase {
	
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
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$app = new \Illuminate\Foundation\Application($request);

		$app['orchestra.installed'] = false;

		$stub = new \Orchestra\Foundation\Installer($app);

		$this->assertFalse($stub->installed());

		$app['orchestra.installed'] = true;

		$this->assertTrue($stub->installed());
	}

	/**
	 * Test Orchestra\Foundation\Installer::checkDatabase() with valid 
	 * database connection.
	 *
	 * @test
	 */
	public function testCheckDatabaseWithValidConnection()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$app  = new \Illuminate\Foundation\Application($request);

		\Illuminate\Support\Facades\DB::setFacadeApplication($app);
		\Illuminate\Support\Facades\DB::swap($dbMock = \Mockery::mock('DB'));

		$dbMock->shouldReceive('connection')
				->once()
				->andReturn(\Mockery::self())
			->shouldReceive('getPdo')
				->once()
				->andReturn(true);

		$stub = new \Orchestra\Foundation\Installer($app);

		$this->assertTrue($stub->checkDatabase());
	}

	/**
	 * Test Orchestra\Foundation\Installer::checkDatabase() with invalid 
	 * database connection.
	 *
	 * @test
	 */
	public function testCheckDatabaseWithInvalidConnection()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$app  = new \Illuminate\Foundation\Application($request);

		\Illuminate\Support\Facades\DB::setFacadeApplication($app);
		\Illuminate\Support\Facades\DB::swap($dbMock = \Mockery::mock('DB'));

		$dbMock->shouldReceive('connection')
				->once()
				->andReturn(\Mockery::self())
			->shouldReceive('getPdo')
				->once()
				->andThrow('PDOException');

		$stub = new \Orchestra\Foundation\Installer($app);

		$this->assertFalse($stub->checkDatabase());
	}
}