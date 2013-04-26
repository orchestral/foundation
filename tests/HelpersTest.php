<?php namespace Orchestra\Foundation\Tests;

class HelpersTest extends \PHPUnit_Framework_TestCase {
	
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
		$this->app = \Mockery::mock('Application');
		$this->app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);
	}
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test orchestra() method.
	 *
	 * @test
	 */
	public function testOrchestraMethod()
	{
		$app = $this->app;
		$app->shouldReceive('make')->with('orchestra.app')->once()->andReturn('foo');

		\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

		$this->assertEquals('foo', orchestra());
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