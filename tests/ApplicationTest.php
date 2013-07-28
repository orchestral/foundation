<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Foundation\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

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

		\Illuminate\Support\Facades\Facade::clearResolvedInstances();
		\Illuminate\Support\Facades\Facade::setFacadeApplication($this->app);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Application::boot() method.
	 *
	 * @test
	 */
	public function testBootMethod()
	{
		$app = $this->app;
		$app['env'] = 'production';
		$app['orchestra.installed'] = false;
		$app['orchestra.acl'] = $acl = m::mock('Acl');
		$app['orchestra.memory'] = $memory = m::mock('Memory');
		$app['orchestra.widget'] = $widget = m::mock('Widget');
		$app['translator'] = $translator = m::mock('Translator');
		$app['url'] = $url = m::mock('Url');
		$app['events'] = $event = m::mock('Event\Dispatcher');
		$app['config'] = $config = m::mock('Config\Manager');

		\Illuminate\Support\Facades\Config::swap($config);

		$acl->shouldReceive('make')->once()->andReturn($acl)
			->shouldReceive('attach')->with($memory)->once()->andReturn($acl);
		$memory->shouldReceive('make')->with()->once()->andReturn($memory)
			->shouldReceive('make')->with('runtime.orchestra')->never()->andReturn($memory)
			->shouldReceive('get')->with('site.name')->once()->andReturn('Orchestra')
			->shouldReceive('put')->with('site.name', 'Orchestra')->never()->andReturn(null);
		$widget->shouldReceive('make')->with('menu.orchestra')->once()->andReturn($widget)
			->shouldReceive('make')->with('menu.app')->once()->andReturn($widget)
			->shouldReceive('add')->andReturn($widget)
			->shouldReceive('title')->once()->andReturn($widget)
			->shouldReceive('link')->once()->andReturn(null);
		$translator->shouldReceive('get')->andReturn('foo');
		$event->shouldReceive('listen')->once()->with('orchestra.ready: admin', 'FooAdminMenuHandler')->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.started')->andReturn(null);
		$config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin')
			->shouldReceive('get')->once()->with('orchestra/foundation::menu')->andReturn('FooAdminMenuHandler');
		$url->shouldReceive('to')->once()->with('admin')->andReturn('admin');

		$stub = new Application($app);
		$stub->boot();

		$this->assertTrue($app['orchestra.installed']);
		$this->assertEquals($widget, $stub->menu());
		$this->assertEquals($acl, $stub->acl());
		$this->assertEquals($memory, $stub->memory());
	}

	/**
	 * Test Orchestra\Foundation\Application::boot() method when database 
	 * is not installed yet.
	 *
	 * @test
	 */
	public function testBootMethodWhenDatabaseIsNotInstalled()
	{
		$app = $this->app;
		$app['env'] = 'production';
		$app['orchestra.installed'] = false;
		$app['orchestra.app'] = $orchestra = m::mock('Orchestra');
		$app['orchestra.acl'] = $acl = m::mock('Acl');
		$app['orchestra.memory'] = $memory = m::mock('Memory');
		$app['orchestra.widget'] = $widget = m::mock('Widget');
		$app['config'] = $config = m::mock('Config\Manager');
		$app['url'] = $url = m::mock('Url\Generator');

		$acl->shouldReceive('make')->once()->andReturn($acl)
			->shouldReceive('attach')->never()->andReturn($acl);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('make')->with('runtime.orchestra')->once()->andReturn($memory)
			->shouldReceive('get')->with('site.name')->once()->andReturn(null)
			->shouldReceive('put')->with('site.name', 'Orchestra Platform')->once()->andReturn(null);
		$widget->shouldReceive('make')->with('menu.orchestra')->once()->andReturn($widget)
			->shouldReceive('make')->with('menu.app')->once()->andReturn($widget)
			->shouldReceive('add')->with('install')->once()->andReturn($widget)
			->shouldReceive('title')->with('Install')->once()->andReturn($widget);
		$config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
		$url->shouldReceive('to')->once()->with('admin/install')->andReturn('admin/install');

		$widget->shouldReceive('link')->with('admin/install');

		$stub = new Application($app);
		$stub->boot();

		$this->assertFalse($app['orchestra.installed']);
	}

	/**
	 * Test Orchestra\Foundation\Application::installed() method.
	 *
	 * @test
	 */
	public function testInstalledMethod()
	{
		$this->app['orchestra.installed'] = false;

		$stub = new Application($this->app);

		$this->assertFalse($stub->installed());

		$this->app['orchestra.installed'] = true;

		$this->assertTrue($stub->installed());
	}

	/**
	 * Test Orchestra\Foundation\Application::illuminate() method.
	 *
	 * @test
	 */
	public function testIlluminateMethod()
	{
		$stub = new Application($this->app);
		$this->assertInstanceOf('\Illuminate\Foundation\Application', $stub->illuminate());
		$this->assertInstanceOf('\Illuminate\Http\Request', $stub->make('request'));
	}

	/**
	 * Test Orchestra\Foundation\Application::handles() method.
	 *
	 * @test
	 */
	public function testHandlesMethod()
	{
		$app = $this->app;
		$app['env'] = 'production';
		$app['orchestra.installed'] = false;
		$app['orchestra.app'] = $orchestra = m::mock('Orchestra');
		$app['orchestra.acl'] = $acl = m::mock('Acl');
		$app['orchestra.memory'] = $memory = m::mock('Memory');
		$app['orchestra.widget'] = $widget = m::mock('Widget');
		$app['orchestra.extension'] = $extension = m::mock('Extension');
		$app['translator'] = $translator = m::mock('Translator');
		$app['url'] = $url = m::mock('Url\Generator');
		$app['events'] = $event = m::mock('Event\Dispatcher');
		$app['config'] = $config = m::mock('Config\Manager');

		\Illuminate\Support\Facades\Config::swap($config);

		$acl->shouldReceive('make')->once()->andReturn($acl)
			->shouldReceive('attach')->with($memory)->once()->andReturn($acl);
		$memory->shouldReceive('make')->with()->once()->andReturn($memory)
			->shouldReceive('make')->with('runtime.orchestra')->never()->andReturn($memory)
			->shouldReceive('get')->with('site.name')->once()->andReturn('Orchestra')
			->shouldReceive('put')->with('site.name', 'Orchestra')->never()->andReturn(null);
		$widget->shouldReceive('make')->with('menu.orchestra')->once()->andReturn($widget)
			->shouldReceive('make')->with('menu.app')->once()->andReturn($widget)
			->shouldReceive('add')->andReturn($widget)
			->shouldReceive('title')->once()->andReturn($widget)
			->shouldReceive('link')->once()->andReturn(null);
		$translator->shouldReceive('get')->andReturn('foo');
		$event->shouldReceive('listen')->once()->with('orchestra.ready: admin', 'FooAdminMenuHandler')->andReturn(null)
			->shouldReceive('fire')->with('orchestra.started')->once()->andReturn(null);
		$config->shouldReceive('get')->times(3)->with('orchestra/foundation::handles', '/')->andReturn('admin')
			->shouldReceive('get')->once()->with('orchestra/foundation::menu')->andReturn('FooAdminMenuHandler');
		$extension->shouldReceive('route')->twice()->with('app', '/')->andReturn('/');
		$url->shouldReceive('to')->once()->with('/')->andReturn('/')
			->shouldReceive('to')->once()->with('info')->andReturn('info')
			->shouldReceive('to')->once()->with('admin')->andReturn('admin')
			->shouldReceive('to')->twice()->with('admin/installer')->andReturn('admin/installer');

		$stub = new Application($app);

		$this->assertEquals('/', $stub->handles('app::/'));
		$this->assertEquals('info', $stub->handles('info'));
		$this->assertEquals('admin/installer', $stub->handles('orchestra::installer'));
		$this->assertEquals('admin/installer', $stub->handles('orchestra::installer/'));
	}
}
