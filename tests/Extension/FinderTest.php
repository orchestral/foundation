<?php namespace Orchestra\Foundation\Tests\Extension;

class FinderTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test constructing a new Orchestra\Foundation\Extension\Finder.
	 *
	 * @test
	 */
	public function testConstructMethod()
	{
		$app = array(
			'path.base' => '/foo/path'
		);

		$stub = new \Orchestra\Foundation\Extension\Finder($app);

		$refl  = new \ReflectionObject($stub);
		$paths = $refl->getProperty('paths');
		$paths->setAccessible(true);

		$this->assertEquals(array('/foo/path/vendor/', '/foo/path/workbench/'), 
			$paths->getValue($stub)); 
	}

	/**
	 * Test Orchestra\Foundation\Extension\Finder::detect() method.
	 *
	 * @test
	 */
	public function testDetectMethod()
	{
		$app = array(
			'path.base' => '/foo/path/',
			'files'     => $fileMock = \Mockery::mock('\Illuminate\Filesystem\Filesystem'),
		);

		$fileMock->shouldReceive('glob')
				->with('/foo/path/vendor/*/*/orchestra.json')
				->once()
				->andReturn(array('/foo/path/vendor/laravel/framework/orchestra.json'))
			->shouldReceive('glob')
				->with('/foo/path/workbench/*/*/orchestra.json')
				->once()
				->andReturn(array())
			->shouldReceive('get')
				->with('/foo/path/vendor/laravel/framework/orchestra.json')
				->once()
				->andReturn('{"name":"Laravel Framework"}');

		$stub = new \Orchestra\Foundation\Extension\Finder($app);
		$manifest = (object) array(
			'path'        => '/foo/path/vendor/laravel/framework/orchestra.json',
			'name'        => 'Laravel Framework',
			'description' => null,
			'version'     => '>0',
			'config'      => array(),
			'require'     => array(),
			'services'    => array(),
		);

		$this->assertEquals(array('laravel/framework' => $manifest), $stub->detect());
	}

	/**
	 * Test Orchestra\Foundation\Extension\Finder::detect() method throws 
	 * exception when unable to parse json manifest file.
	 *
	 * @expectedException \Orchestra\Foundation\Extension\ManifestRuntimeException
	 */
	public function testDetectMethodThrowsException()
	{
		$app = array(
			'path.base' => '/foo/path/',
			'files'     => $fileMock = \Mockery::mock('\Illuminate\Filesystem\Filesystem'),
		);

		$fileMock->shouldReceive('glob')
				->with('/foo/path/vendor/*/*/orchestra.json')
				->once()
				->andReturn(array('/foo/path/vendor/laravel/framework/orchestra.json'))
			->shouldReceive('glob')
				->with('/foo/path/workbench/*/*/orchestra.json')
				->andReturn(array())
			->shouldReceive('get')
				->with('/foo/path/vendor/laravel/framework/orchestra.json')
				->once()
				->andReturn('{"name":"Laravel Framework}');

		with(new \Orchestra\Foundation\Extension\Finder($app))->detect();
	}
}