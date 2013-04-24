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
	 * Test construct Orchestra\Foundation\Installation\Requirement.
	 *
	 * @test
	 */
	public function testConstructMethod()
	{
		$app         = $this->app;
		$stub        = new \Orchestra\Foundation\Installation\Requirement($app);
		$refl        = new \ReflectionObject($stub);
		$checklist   = $refl->getProperty('checklist');
		$installable = $refl->getProperty('installable');

		$checklist->setAccessible(true);
		$installable->setAccessible(true);

		$checklist->setValue($stub, array('foo', 'bar'));
		$installable->setValue($stub, true);

		$this->assertEquals(array('foo', 'bar'), $stub->getChecklist());
		$this->assertTrue($stub->isInstallable());
	}


	/**
	 * Test Orchestra\Foundation\Installation\Requirement::check() method.
	 *
	 * @test
	 */
	public function testCheckMethod()
	{
		$app  = $this->app;
		$stub = \Mockery::mock('\Orchestra\Foundation\Installation\Requirement[checkDatabaseConnection,checkWritableStorage,checkWritableAsset]', array($app));
		$stub->shouldReceive('checkDatabaseConnection')
				->once()->andReturn(array('is' => true, 'explicit' => true, 'should' => true))
			->shouldReceive('checkWritableStorage')
				->once()->andReturn(array('is' => false, 'explicit' => true, 'should' => true))
			->shouldReceive('checkWritableAsset')
				->once()->andReturn(array('is' => true, 'explicit' => true, 'should' => true));

		$this->assertFalse($stub->check());
		$this->assertFalse($stub->isInstallable());
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
		$this->assertTrue($result['explicit']);
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
		$this->assertTrue($result['explicit']);
	}

	/**
	 * Test Orchestra\Foundation\Installation\Requirement::checkWritableStorage() 
	 * method.
	 *
	 * @test
	 */
	public function testCheckWritableStorageMethod()
	{
		$app = $this->app;
		$app['path.storage'] = '/foo/storage/';
		$app['html'] = $html = \Mockery::mock('Html');
		$app['files'] = $file = \Mockery::mock('File');

		$html->shouldReceive('create')
			->with('code', 'storage', array('title' => '/foo/storage/'))
			->once()->andReturn('');
		$file->shouldReceive('isWritable')->with('/foo/storage/')->once()->andReturn(true);

		$stub = new \Orchestra\Foundation\Installation\Requirement($app);

		$result = $stub->checkWritableStorage();

		$this->assertTrue($result['is']);
		$this->assertTrue($result['explicit']);
	}

	/**
	 * Test Orchestra\Foundation\Installation\Requirement::checkWritableAsset() 
	 * method.
	 *
	 * @test
	 */
	public function testCheckWritableAssetMethod()
	{
		$app = $this->app;
		$app['path.public'] = '/foo/public/';
		$app['html'] = $html = \Mockery::mock('Html');
		$app['files'] = $file = \Mockery::mock('File');

		$html->shouldReceive('create')
			->with('code', 'public/packages', \Mockery::any())
			->once()->andReturn('');
		$file->shouldReceive('isWritable')->with('/foo/public/packages/')->once()->andReturn(true);

		$stub = new \Orchestra\Foundation\Installation\Requirement($app);

		$result = $stub->checkWritableAsset();
		
		$this->assertTrue($result['is']);
		$this->assertFalse($result['explicit']);
	}
}