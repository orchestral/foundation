<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Services\TestCase;

class MacroTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test HTML::title() macro.
	 *
	 * @test
	 */
	public function testHtmlTitleMacro()
	{
		\Orchestra\Support\Facades\App::shouldReceive('memory')->twice()
			->andReturn($memory = m::mock('Memory'));
		
		$memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo')
			->shouldReceive('get')->once()->with('site.format.title', ':pageTitle &mdash; :siteTitle')
			->andReturn(':pageTitle &mdash; :siteTitle');

		$this->assertEquals('<title>Foo</title>', \Illuminate\Support\Facades\HTML::title());
	}

	/**
	 * Test HTML::title() macro with page title.
	 *
	 * @test
	 */
	public function testHtmlTitleMacroWithPageTitle()
	{
		\Orchestra\Support\Facades\App::shouldReceive('memory')->twice()
			->andReturn($memory = m::mock('Memory'));
		\Orchestra\Support\Facades\Site::shouldReceive('get')->once()
			->with('title', '')->andReturn('Foobar');
		
		$memory->shouldReceive('get')->once()->with('site.name', '')->andReturn('Foo')
			->shouldReceive('get')->once()->with('site.format.title', ':pageTitle &mdash; :siteTitle')
			->andReturn(':pageTitle &mdash; :siteTitle');

		$this->assertEquals('<title>Foobar &mdash; Foo</title>', \Illuminate\Support\Facades\HTML::title());
	}
}
