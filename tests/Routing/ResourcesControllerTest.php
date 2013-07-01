<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

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
			'foo' => new \Illuminate\Support\Fluent(array(
				'visible' => true,
				'name'    => 'Foo',
			)),
		);

		\Orchestra\Support\Facades\Resources::shouldReceive('all')->once()
			->andReturn($resources);
		\Orchestra\Support\Facades\Table::shouldReceive('of')->once()
			->with('orchestra.resources: list', m::type('Closure'))->andReturn('table');
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
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
			'laravel' => new \Illuminate\Support\Fluent(array(
				'visible' => true,
				'name'    => 'Laravel',
			)),
		);

		\Orchestra\Support\Facades\Resources::shouldReceive('all')->once()
			->andReturn($resources);
		\Orchestra\Support\Facades\Resources::shouldReceive('call')->once()
			->with('laravel', array('index'))->andReturn('laravel');
		\Orchestra\Support\Facades\Resources::shouldReceive('response')->once()
			->with('laravel', m::type('Closure'))->andReturnUsing(
				function ($n, $c)
				{
					return $c($n);
				});
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::resources.page', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/resources/laravel/index');
		$this->assertResponseOk();
	}
}
