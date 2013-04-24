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
		$app['orchestra.acl'] = $acl = \Mockery::mock('Acl');
		$app['orchestra.memory'] = $memory = \Mockery::mock('Memory');
		$app['orchestra.widget'] = $widget = \Mockery::mock('Widget');
		$app['translator'] = $translator = \Mockery::mock('Translator');
		$app['url'] = ($url = \Mockery::mock('Url'));

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
		$translator->shouldReceive('trans')->andReturn('foo');
		$config->shouldReceive('get')->andReturn('/');
		$url->shouldReceive('to')->once()->with('/', array(), null)->andReturn('/');

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
			
		$url->shouldReceive('to')->with('admin/install', array(), null)->andReturn('admin/install');
		$config->shouldReceive('get')->with('orchestra/foundation::handles', '/')->andReturn('admin');

		\Illuminate\Support\Facades\Config::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::swap($config);

		$widget->shouldReceive('link')->with(handles('orchestra/foundation::install'));

		$stub = new \Orchestra\Foundation\Application($app);
		$stub->start();

		$this->assertFalse($app['orchestra.installed']);
	}
	
	/**
	 * Test Orchestra\Foundation\Application::illuminate() method.
	 *
	 * @test
	 */
	public function testIlluminateMethod()
	{
		$stub = new \Orchestra\Foundation\Application($this->app);
		$this->assertEquals($this->app, $stub->illuminate());
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
}