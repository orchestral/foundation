<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;

class HelpersTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app = m::mock('Application');
		$this->app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);
	}
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
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
		$orchestra = m::mock('\Orchestra\Foundation\Application');

		\Orchestra\Support\Facades\App::swap($orchestra);

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
		$orchestra = m::mock('\Orchestra\Foundation\Application');

		\Orchestra\Support\Facades\App::swap($orchestra);

		$orchestra->shouldReceive('handles')->once()->with('app::foo')->andReturn('foo');

		$this->assertEquals('foo', handles('app::foo'));
	}
	
}
