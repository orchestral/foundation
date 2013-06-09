<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Services\TestCase;

class HelpersTest extends TestCase {

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
