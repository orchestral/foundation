<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

class AdminControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Routing\AdminController filters.
	 */
	public function testFilters()
	{
		\Illuminate\Support\Facades\Event::swap($event = m::mock('\Illuminate\Events\Dispatcher'));

		$event->shouldReceive('fire')->once()->with('orchestra.started: admin')->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.ready: admin')->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.done: admin')->andReturn(null);

		$stub = new StubAdminController;
		$refl = new \ReflectionObject($stub);

		$callbackFilters = $refl->getProperty('callbackFilters');
		$filters         = $refl->getProperty('filters');

		$callbackFilters->setAccessible(true);
		$filters->setAccessible(true);

		$stubCallbacks = $callbackFilters->getValue($stub);
		$stubFilters   = $filters->getValue($stub);

		$expectedFilters = array('orchestra.installable');

		foreach ($stubCallbacks as $key => $callback)
		{
			$expectedFilters[] = $key;
			call_user_func($callback);
		}

		foreach ($stubFilters as $filter)
		{
			$this->assertContains($filter->run, $expectedFilters);
		}
	}
}

class StubAdminController extends \Orchestra\Routing\AdminController {}
