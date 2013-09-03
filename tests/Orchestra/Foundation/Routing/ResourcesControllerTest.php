<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\Resources;
use Orchestra\Support\Facades\Table;

class ResourcesControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/resources
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		$resources = array(
			'foo' => new Fluent(array(
				'visible' => true,
				'name'    => 'Foo',
			)),
		);

		Resources::shouldReceive('all')->once()->andReturn($resources);
		Table::shouldReceive('of')->once()
			->with('orchestra.resources: list', m::type('Closure'))->andReturn('table');
		View::shouldReceive('make')->once()
			->with('orchestra/foundation::resources.index', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/resources');
		$this->assertResponseOk();
	}

	/**
	 * Test GET /admin/resources/laravel
	 *
	 * @test
	 */
	public function testGetCallAction()
	{
		$resources = array(
			'laravel' => new Fluent(array(
				'visible' => true,
				'name'    => 'Laravel',
			)),
		);

		Resources::shouldReceive('all')->once()->andReturn($resources);
		Resources::shouldReceive('call')->once()->with('laravel', array('index'))->andReturn('laravel');
		Resources::shouldReceive('response')->once()
			->with('laravel', m::type('Closure'))->andReturnUsing(
				function ($n, $c)
				{
					return $c($n);
				});
		View::shouldReceive('make')->once()
			->with('orchestra/foundation::resources.page', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/resources/laravel/index');
		$this->assertResponseOk();
	}
}
