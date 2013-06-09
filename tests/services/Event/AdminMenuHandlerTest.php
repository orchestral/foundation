<?php namespace Orchestra\Foundation\Tests\Services\Event;

use Mockery as m;
use Orchestra\Services\Event\AdminMenuHandler;

class AdminMenuHandlerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Services\Event\AdminMenuHandler::handle() method.
	 *
	 * @test
	 */
	public function testHandleMethod()
	{
		\Orchestra\Support\Facades\App::swap($app = m::mock('Orchestra'));
		\Orchestra\Support\Facades\Resources::swap($resources = m::mock('Resources'));
		\Illuminate\Support\Facades\Auth::swap($auth = m::mock('Auth\Guard'));

		$app->shouldReceive('acl')->once()->andReturn($acl = m::mock('Acl'))
			->shouldReceive('menu')->once()->andReturn($menu = m::mock('Menu'))
			->shouldReceive('make')->once()->with('translator')->andReturn($translator = m::mock('Translator'));

		$acl->shouldReceive('can')->once()->with('manage-users')->andReturn(true);
		$translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.users.list')->andReturn('user');
		$app->shouldReceive('handles')->once()->with('orchestra/foundation::users')->andReturn('user');
		$menu->shouldReceive('add')->once()->with('users')->andReturn($menu)
			->shouldReceive('title')->once()->with('user')->andReturn($menu)
			->shouldReceive('link')->once()->with('user')->andReturn(null);

		$acl->shouldReceive('can')->once()->with('manage-orchestra')->andReturn(true);
		$translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.extensions.list')->andReturn('extension');
		$app->shouldReceive('handles')->once()->with('orchestra/foundation::extensions')->andReturn('extension');
		$menu->shouldReceive('add')->once()->with('extensions', '>:home')->andReturn($menu)
			->shouldReceive('title')->once()->with('extension')->andReturn($menu)
			->shouldReceive('link')->once()->with('extension')->andReturn(null);
		$translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.settings.list')->andReturn('setting');
		$app->shouldReceive('handles')->once()->with('orchestra/foundation::settings')->andReturn('setting');
		$menu->shouldReceive('add')->once()->with('settings')->andReturn($menu)
			->shouldReceive('title')->once()->with('setting')->andReturn($menu)
			->shouldReceive('link')->once()->with('setting')->andReturn(null);

		$auth->shouldReceive('guest')->once()->andReturn(false);

		$foo = new \Illuminate\Support\Fluent(array(
			'name'    => 'Foo',
			'visible' => true,
		));

		$resources->shouldReceive('all')->once()->andReturn(array('foo' => $foo));

		$translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.resources.list')->andReturn('resource');
		$app->shouldReceive('handles')->once()->with('orchestra/foundation::resources')->andReturn('resource');
		$menu->shouldReceive('add')->once()->with('resources', '>:extensions')->andReturn($menu)
			->shouldReceive('title')->once()->with('resource')->andReturn($menu)
			->shouldReceive('link')->once()->with('resource')->andReturn(null);
		
		$app->shouldReceive('handles')->once()->with('orchestra/foundation::resources/foo')->andReturn('foo-resource');
		$menu->shouldReceive('add')->once()->with('foo', '^:resources')->andReturn($menu)
			->shouldReceive('title')->once()->with('Foo')->andReturn($menu)
			->shouldReceive('link')->once()->with('foo-resource')->andReturn(null);
		
		$stub = new AdminMenuHandler;
		$stub->handle();
	}
}
