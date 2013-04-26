<?php namespace Orchestra\Foundation\Tests;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$request = \Mockery::mock('\Illuminate\Http\Request');
		$request->shouldReceive('ajax')->andReturn(null);

		$this->app = new \Illuminate\Foundation\Application($request);
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test Orchestra\Foundation\Application::start() method.
	 *
	 * @test
	 */
	public function testStartMethod()
	{
		$app = $this->app;
		$app['env'] = 'production';
		$app['orchestra.installed'] = false;
		$app['orchestra.app'] = $orchestra = \Mockery::mock('App');
		$app['orchestra.acl'] = $acl = \Mockery::mock('Acl');
		$app['orchestra.memory'] = $memory = \Mockery::mock('Memory');
		$app['orchestra.widget'] = $widget = \Mockery::mock('Widget');
		$app['translator'] = $translator = \Mockery::mock('Translator');
		$app['url'] = ($url = \Mockery::mock('Url'));
		$app['events'] = $event = \Mockery::mock('Event');

		\Illuminate\Support\Facades\Config::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::swap($config = \Mockery::mock('Config'));

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
		$orchestra->shouldReceive('handles')->once()->with('orchestra/foundation::/')->andReturn('/');
		$event->shouldReceive('listen')->with('orchestra.ready: admin', 'Orchestra\Services\Event\AdminMenuHandler')->once()->andReturn(null)
			->shouldReceive('fire')->with('orchestra.started')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::setFacadeApplication($app);
		\Orchestra\Support\Facades\App::swap($orchestra);

		$stub = new \Orchestra\Foundation\Application($app);
		$stub->start();

		$this->assertTrue($app['orchestra.installed']);
		$this->assertEquals($widget, $stub->menu());
		$this->assertEquals($acl, $stub->acl());
		$this->assertEquals($memory, $stub->memory());
	}

	/**
	 * Test Orchestra\Foundation\Application::start() method when database 
	 * is not installed yet.
	 *
	 * @test
	 */
	public function testStartMethodWhenDatabaseIsNotInstalled()
	{
		$app = $this->app;
		$app['orchestra.installed'] = false;
		$app['orchestra.app'] = $orchestra = \Mockery::mock('App');
		$app['orchestra.acl'] = $acl = \Mockery::mock('Acl');
		$app['orchestra.memory'] = $memory = \Mockery::mock('Memory');
		$app['orchestra.widget'] = $widget = \Mockery::mock('Widget');
		$app['config'] = $config = \Mockery::mock('Config');
		$app['env'] = 'production';
		$app['url'] = $url = \Mockery::mock('Url');

		$acl->shouldReceive('make')->once()->andReturn($acl)
			->shouldReceive('attach')->never()->andReturn($acl);
		$memory->shouldReceive('make')->with()->once()->andReturn($memory)
			->shouldReceive('make')->with('runtime.orchestra')->once()->andReturn($memory)
			->shouldReceive('get')->with('site.name')->once()->andReturn(null)
			->shouldReceive('put')->with('site.name', 'Orchestra')->once()->andReturn(null);
		$widget->shouldReceive('make')->with('menu.orchestra')->once()->andReturn($widget)
			->shouldReceive('make')->with('menu.app')->once()->andReturn($widget)
			->shouldReceive('add')->with('install')->once()->andReturn($widget)
			->shouldReceive('title')->with('Install')->once()->andReturn($widget);
		$orchestra->shouldReceive('handles')->once()->with('orchestra/foundation::install')->andReturn('admin/install');

		\Orchestra\Support\Facades\App::setFacadeApplication($app);
		\Orchestra\Support\Facades\App::swap($orchestra);

		$widget->shouldReceive('link')->with('admin/install');

		$stub = new \Orchestra\Foundation\Application($app);
		$stub->start();

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

		$stub = new \Orchestra\Foundation\Application($this->app);

		$this->assertFalse($stub->installed());

		$this->app['orchestra.installed'] = true;

		$this->assertTrue($stub->installed());
	}

	/**
	 * Test Orchestra\Foundation\Application::handles() method.
	 *
	 * @test
	 */
	public function testHandlesMethod()
	{
		$app = $this->app;
		$app['config'] = ($config = \Mockery::mock('Config'));
		$app['url'] = ($url = \Mockery::mock('Url'));

		$config->shouldReceive('get')->twice()->with('orchestra/extension::handles.app', '/')->andReturn('/')
			->shouldReceive('get')->twice()->with('orchestra/extension::handles.orchestra/foundation', '/')->andReturn('admin');
		$url->shouldReceive('to')->once()->with('/')->andReturn('/')
			->shouldReceive('to')->once()->with('info')->andReturn('info')
			->shouldReceive('to')->twice()->with('admin/installer')->andReturn('admin/installer');

		$stub = new \Orchestra\Foundation\Application($app);

		$this->assertEquals('/', $stub->handles('app::/'));
		$this->assertEquals('info', $stub->handles('info'));
		$this->assertEquals('admin/installer', $stub->handles('orchestra/foundation::installer'));
		$this->assertEquals('admin/installer', $stub->handles('orchestra/foundation::installer/'));
	}
}