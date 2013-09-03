<?php namespace Orchestra\Foundation\Services\Event\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Support\Facades\Acl;
use Orchestra\Foundation\Services\Event\RoleObserver;

class RoleObserverTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Facade::clearResolvedInstances();
		Facade::setFacadeApplication(new Container);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Event\RoleObserver::creating() 
	 * method.
	 *
	 * @test
	 */
	public function testCreatingMethod()
	{
		Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
		$acl->shouldReceive('addRole')->once()->with('foo')->andReturn(null);

		$stub = new RoleObserver;
		$stub->creating($model);
	}

	/**
	 * Test Orchestra\Foundation\Services\Event\RoleObserver::deleting() 
	 * method.
	 *
	 * @test
	 */
	public function testDeletingMethod()
	{
		Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
		$acl->shouldReceive('removeRole')->once()->with('foo')->andReturn(null);

		$stub = new RoleObserver;
		$stub->deleting($model);
	}

	/**
	 * Test Orchestra\Foundation\Services\Event\RoleObserver::updating() 
	 * method.
	 *
	 * @test
	 */
	public function testUpdatingMethod()
	{
		Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getOriginal')->once()->with('name')->andReturn('foo')
			->shouldReceive('getAttribute')->once()->with('name')->andReturn('foobar')
			->shouldReceive('getDeletedAtColumn')->once()->andReturn('deleted_at')
			->shouldReceive('isSoftDeleting')->once()->andReturn(false);
		$acl->shouldReceive('renameRole')->once()->with('foo', 'foobar')->andReturn(null);

		$stub = new RoleObserver;
		$stub->updating($model);
	}

	/**
	 * Test Orchestra\Foundation\Services\Event\RoleObserver::updating() 
	 * method for restoring.
	 *
	 * @test
	 */
	public function testUpdatingMethodForRestoring()
	{
		Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getOriginal')->once()->with('name')->andReturn('foo')
			->shouldReceive('getAttribute')->once()->with('name')->andReturn('foobar')
			->shouldReceive('getDeletedAtColumn')->once()->andReturn('deleted_at')
			->shouldReceive('isSoftDeleting')->once()->andReturn(true)
			->shouldReceive('getOriginal')->once()->with('deleted_at')->andReturn('0000-00-00 00:00:00')
			->shouldReceive('getAttribute')->once()->with('deleted_at')->andReturn(null);
		$acl->shouldReceive('addRole')->once()->with('foobar')->andReturn(null);

		$stub = new RoleObserver;
		$stub->updating($model);
	}
}
