<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
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

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Installed setup.
     *
     */
    private function installableContainer()
    {
        $app = $this->app;
        $app['env'] = 'production';
        $app['orchestra.installed'] = false;
        $app['orchestra.acl'] = $acl = m::mock('Acl');
        $app['orchestra.memory'] = $memory = m::mock('Memory');
        $app['orchestra.extension'] = $extension = m::mock('Extension');
        $app['orchestra.widget'] = $widget = m::mock('Widget');
        $app['translator'] = $translator = m::mock('Translator');
        $app['events'] = $event = m::mock('Event\Dispatcher');
        $app['config'] = $config = m::mock('Config\Manager');
        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->once()->with($memory)->andReturn($acl);
        $memory->shouldReceive('make')->once()->andReturn($memory)
            ->shouldReceive('make')->never()->with('runtime.orchestra')->andReturn($memory)
            ->shouldReceive('get')->once()->with('site.name')->andReturn('Orchestra')
            ->shouldReceive('put')->never()->with('site.name', 'Orchestra')->andReturn(null);
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add')->andReturn($widget)
            ->shouldReceive('title')->once()->andReturn($widget)
            ->shouldReceive('link')->once()->andReturn(null);
        $translator->shouldReceive('get')->andReturn('foo');
        $event->shouldReceive('listen')->once()
                ->with('orchestra.ready: admin', 'Orchestra\Foundation\AdminMenuHandler')->andReturn(null)
            ->shouldReceive('fire')->once()->with('orchestra.started')->andReturn(null);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);

        return $app;
    }

    /**
     * Test Orchestra\Foundation\Application::boot() method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app  = $this->installableContainer();
        $stub = new Application($app);
        $stub->boot();

        $this->assertTrue($app['orchestra.installed']);
        $this->assertEquals($app['orchestra.widget'], $stub->menu());
        $this->assertEquals($app['orchestra.acl'], $stub->acl());
        $this->assertEquals($app['orchestra.memory'], $stub->memory());
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
        $app['orchestra.acl'] = $acl = m::mock('Acl');
        $app['orchestra.memory'] = $memory = m::mock('Memory');
        $app['orchestra.widget'] = $widget = m::mock('Widget');
        $app['config'] = $config = m::mock('Config\Manager');
        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->never()->andReturn($acl);
        $memory->shouldReceive('make')->once()->andReturn($memory)
            ->shouldReceive('make')->once()->with('runtime.orchestra')->andReturn($memory)
            ->shouldReceive('get')->once()->with('site.name')->andReturn(null)
            ->shouldReceive('put')->once()->with('site.name', 'Orchestra Platform')->andReturn(null)
            ->shouldReceive('get')->never()->with('email')->andReturn('memory.email');
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add')->once()->with('install')->andReturn($widget)
            ->shouldReceive('title')->once()->with('Install')->andReturn($widget);
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin')
            ->shouldReceive('set')->never()->with('mail', 'memory.email')->andReturn(null);
        $widget->shouldReceive('link')->with('http://localhost/admin/install')->once();

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
     * Test Orchestra\Foundation\Application::group() method.
     *
     * @test
     */
    public function testGroupMethod()
    {
        $app  = $this->installableContainer();
        $stub = new Application($app);

        $expected = array(
            'before' => 'auth',
            'prefix' => 'admin',
            'domain' => null,
        );

        $this->assertEquals($expected, $stub->group('orchestra', 'admin', array('before' => 'auth')));
    }

    /**
     * Test Orchestra\Foundation\Application::handles() method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = $this->installableContainer();
        $extension = $app['orchestra.extension'];
        $app['url'] = $url = m::mock('Url');

        $appRoute = m::mock('\Orchestra\Extension\RouteGenerator');

        $appRoute->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);
        $url->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');

        $stub = new Application($app);
        $stub->boot();

        $this->assertEquals('/', $stub->handles('app::/'));
        $this->assertEquals('info?foo=bar', $stub->handles('info?foo=bar'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer/'));
    }
}
