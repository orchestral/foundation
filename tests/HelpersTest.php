<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Orchestra\Support\Facades\App;

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
		$this->app = new \Illuminate\Foundation\Application;
		$this->app['translator'] = $trans = m::mock('Translator');
		$this->app['orchestra.app'] = $orchestra = m::mock('\Orchestra\Foundation\Application');

		Facade::clearResolvedInstances();
		Facade::setFacadeApplication($this->app);

		$trans->shouldReceive('trans')->andReturn('translated');
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
		$this->assertInstanceOf('\Orchestra\Foundation\Application', orchestra());
	}

	/**
	 * Test memorize() method.
	 *
	 * @test
	 */
	public function testMemorizeMethod()
	{
		$orchestra = m::mock('\Orchestra\Foundation\Application');

		App::swap($orchestra);

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

		App::swap($orchestra);

		$orchestra->shouldReceive('handles')->once()->with('app::foo')->andReturn('foo');

		$this->assertEquals('foo', handles('app::foo'));
	}

	/**
	 * Test resources() method.
	 *
	 * @test
	 */
	public function testResourcesMethod()
	{
		$orchestra = m::mock('\Orchestra\Foundation\Application');

		App::swap($orchestra);

		$orchestra->shouldReceive('handles')->once()
			->with('orchestra/foundation::resources/foo')->andReturn('foo');

		$this->assertEquals('foo', resources('foo'));
	}
	
}
