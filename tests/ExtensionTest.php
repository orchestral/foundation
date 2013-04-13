<?php namespace Orchestra\Foundation;

class ExtensionTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Extension::detect() method.
	 *
	 * @test
	 */
	public function testDetectMethod()
	{
		$app  = array(
			'orchestra.extension.finder' => ($finder = \Mockery::mock('Finder')),
			'orchestra.memory'           => ($memory = \Mockery::mock('Memory')),
		);

		$finder->shouldReceive('detect')
				->once()
				->andReturn('foo');

		$memory->shouldReceive('make')
				->once()
				->andReturn($memory)
			->shouldReceive('put')
				->with('extensions.available', 'foo')
				->andReturn('foobar');

		$stub = new \Orchestra\Foundation\Extension($app);
		$this->assertEquals('foo', $stub->detect());
	}
}