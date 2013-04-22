<?php namespace Orchestra\Foundation\Tests\Installation;

class RequirementTest extends \PHPUnit_Framework_TestCase {

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

		\Illuminate\Support\Facades\DB::setFacadeApplication($this->app);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->app);

		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Installation\Requirement::check() method.
	 *
	 * @test
	 */
	public function testCheckMethod()
	{
		$app  = $this->app;
		$stub = \Mockery::mock('\Orchestra\Foundation\Installation\Requirement[checkDatabaseConnection,checkWritableStorage,checkWritableAsset]');
		$stub->shouldReceive('checkDatabaseConnection')
				->once()->andReturn(array())
			->shouldReceive('checkWritableStorage')
				->once()->andReturn(array())
			->shouldReceive('checkWritableAsset')
				->once()->andReturn(array());

		$stub->check();
	}

	/**
	 * Test Orchestra\Foundation\Installation\Foundation::checkDatabaseConnection() 
	 * with valid database connection.
	 *
	 * @test
	 */
	public function testCheckDatabaseConnectionWithValidConnection()
	{
		\Illuminate\Support\Facades\DB::swap($db = \Mockery::mock('DB'));

		$db->shouldReceive('connection')
				->once()
				->andReturn($db)
			->shouldReceive('getPdo')
				->once()
				->andReturn(true);

		$stub = new \Orchestra\Foundation\Installation\Requirement($this->app);
		$result = $stub->checkDatabaseConnection(); 

		$this->assertTrue($result['is']);
	}

	/**
	 * Test Orchestra\Foundation\Installation\Foundation::checkDatabaseConnection() 
	 * with invalid database connection.
	 *
	 * @test
	 */
	public function testCheckDatabaseConnectionWithInvalidConnection()
	{
		\Illuminate\Support\Facades\DB::swap($db = \Mockery::mock('DB'));

		$db->shouldReceive('connection')
				->once()
				->andReturn($db)
			->shouldReceive('getPdo')
				->once()
				->andThrow('PDOException');

		$stub   = new \Orchestra\Foundation\Installation\Requirement($this->app);
		$result = $stub->checkDatabaseConnection(); 

		$this->assertFalse($result['is']);
	}
}