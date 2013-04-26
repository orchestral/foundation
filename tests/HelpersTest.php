<?php namespace Orchestra\Foundation\Tests;

class HelpersTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$app = \Mockery::mock('Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Orchestra\Support\Facades\App::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::setFacadeApplication($app);
	}
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test memorize() method.
	 *
	 * @test
	 */
	public function testMemorizeMethod()
	{
		\Orchestra\Support\Facades\App::swap($orchestra = \Mockery::mock('\Orchestra\Foundation\Application'));

		$orchestra->shouldReceive('memory')->once()->andReturn($orchestra)
			->shouldReceive('get')->once()->with('site.name', null)->andReturn('Orchestra');

		$this->assertEquals('Orchestra', memorize('site.name'));
	}

	/**
	 * Test handles() method.
	 *
	 * @test
	 */
	public function testHandlesMethod()
	{
		\Orchestra\Support\Facades\App::swap($orchestra = \Mockery::mock('\Orchestra\Foundation\Application'));

		$orchestra->shouldReceive('handles')->once()->with('app::foo')->andReturn('foo');

		$this->assertEquals('foo', handles('app::foo'));
	}
	
}