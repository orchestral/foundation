<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

class ExtensionsControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/extensions
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		\Orchestra\Support\Facades\Extension::shouldReceive('detect')->once()
			->andReturn('foo');
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::extensions.index', array('extensions' => 'foo'))
			->andReturn('foo');

		$this->call('GET', 'admin/extensions');
		$this->assertResponseOk();
	}

	/**
	 * Test GET /admin/extensions/activate/(:name)
	 *
	 * @test
	 */
	public function testGetActivateAction()
	{
		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(false);
		\Orchestra\Support\Facades\Extension::shouldReceive('activate')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::extensions')->andReturn('extensions');

		$this->call('GET', 'admin/extensions/activate/laravel.framework');
		$this->assertRedirectedTo('extensions');
	}

	/**
	 * Test GET /admin/extensions/activate/(:name)
	 *
	 * @test
	 */
	public function testGetDeactivateAction()
	{
		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Extension::shouldReceive('deactivate')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::extensions')->andReturn('extensions');

		$this->call('GET', 'admin/extensions/deactivate/laravel.framework');
		$this->assertRedirectedTo('extensions');
	}

	/**
	 * Test GET /admin/extensions/configure/(:name)
	 *
	 * @test
	 */
	public function testGetConfigureAction()
	{
		$memory = m::mock('Memory');
		$memory->shouldReceive('get')->once()
				->with('extensions.active.laravel/framework.config', array())->andReturn(array())
			->shouldReceive('get')->once()
				->with('extension_laravel/framework', array())->andReturn(array())
			->shouldReceive('get')->once()
				->with('extensions.available.laravel/framework.name', 'laravel/framework')
				->andReturn('Laravel Framework');

		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.extension: laravel/framework', m::type('Closure'))->andReturn('form');
		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::extensions.configure', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/extensions/configure/laravel.framework');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/extensions/configure/(:name)
	 *
	 * @test
	 */
	public function testPostConfigureAction()
	{
		$input = array(
			'handles' => 'foo',
			'_token'  => 'somesessiontoken', 
		);

		$memory = m::mock('Memory');
		$memory->shouldReceive('get')->once()
				->with('extension.active.laravel/framework.config', array())->andReturn(array())
			->shouldReceive('put')->once()
				->with('extensions.active.laravel/framework.config', array('handles' => 'foo'))->andReturn(null)
			->shouldReceive('put')->once()
				->with('extension_laravel/framework', array('handles' => 'foo'))->andReturn(null);

		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::extensions')->andReturn('extensions');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);

		$this->call('POST', 'admin/extensions/configure/laravel.framework', $input);
		$this->assertRedirectedTo('extensions');
	}

	/**
	 * Test GET /admin/extensions/update/(:name)
	 *
	 * @test
	 */
	public function testGetUpdateAction()
	{
		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Extension::shouldReceive('publish')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::extensions')->andReturn('extensions');

		$this->call('GET', 'admin/extensions/update/laravel.framework');
		$this->assertRedirectedTo('extensions');
	}

	/**
	 * Test GET /admin/extensions/update/(:name) with activation error.
	 *
	 * @test
	 */
	public function testGetUpdateActionGivenActivationError()
	{
		\Orchestra\Support\Facades\Extension::shouldReceive('started')->once()
			->with('laravel/framework')->andReturn(true);
		\Orchestra\Support\Facades\Extension::shouldReceive('publish')->once()
			->with('laravel/framework')->andThrow('\Orchestra\Extension\FilePermissionException');
		\Orchestra\Support\Facades\Publisher::shouldReceive('queue')->once()
			->with('laravel/framework')->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::publisher')->andReturn('publisher');

		$this->call('GET', 'admin/extensions/update/laravel.framework');
		$this->assertRedirectedTo('publisher');
	}
}
