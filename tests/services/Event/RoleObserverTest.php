<?php namespace Orchestra\Foundation\Tests\Services\Event;

use Mockery as m;
use Orchestra\Services\Event\RoleObserver;

class RoleObserverTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Services\Event\RoleObserver::creating() method.
	 *
	 * @test
	 */
	public function testCreatingMethod()
	{
		\Orchestra\Support\Facades\Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
		$acl->shouldReceive('addRole')->once()->with('foo')->andReturn(null);

		$stub = new RoleObserver;
		$stub->creating($model);
	}

	/**
	 * Test Orchestra\Services\Event\RoleObserver::deleting() method.
	 *
	 * @test
	 */
	public function testDeletingMethod()
	{
		\Orchestra\Support\Facades\Acl::swap($acl = m::mock('Acl'));
		$model = m::mock('Role');

		$model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
		$acl->shouldReceive('removeRole')->once()->with('foo')->andReturn(null);

		$stub = new RoleObserver;
		$stub->deleting($model);
	}

	/**
	 * Test Orchestra\Services\Event\RoleObserver::updating() method.
	 *
	 * @test
	 */
	public function testUpdatingMethod()
	{
		\Orchestra\Support\Facades\Acl::swap($acl = m::mock('Acl'));
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
	 * Test Orchestra\Services\Event\RoleObserver::updating() method for 
	 * restoring.
	 *
	 * @test
	 */
	public function testUpdatingMethodForRestoring()
	{
		\Orchestra\Support\Facades\Acl::swap($acl = m::mock('Acl'));
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
