<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
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
        unset($this->app);
        m::close();
    }

    /**
     * Get installable mocks setup
     *
     * @return \Mockery
     */
    private function getInstallableContainerSetup()
    {
        $app = $this->app;
        $app['env'] = 'production';
        $app['orchestra.installed'] = false;
        $app['orchestra.acl'] = $acl = m::mock('\Orchestra\Auth\Acl\Container');
        $app['orchestra.mail'] = $mailer = m::mock('\Orchestra\Notifier\Mailer[attach]');
        $app['orchestra.memory'] = $memory = m::mock('\Orchestra\Memory\MemoryManager[make]');
        $app['orchestra.notifier'] = $notifier = m::mock('\Orchestra\Notifier\NotifierManager[setDefaultDriver]');
        $app['orchestra.widget'] = $widget = m::mock('\Orchestra\Widget\MenuWidgetHandler');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator');
        $app['events'] = $event = m::mock('\Illuminate\Events\Dispatcher[listen,fire]');
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository[get,set]');
        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $memoryProvider = m::mock('\Orchestra\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturn('Orchestra')
            ->shouldReceive('put')->never()->with('site.name', 'Orchestra')->andReturnNull();

        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->once()->with($memoryProvider)->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider)
            ->shouldReceive('make')->never()->with('runtime.orchestra')->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add')->andReturn($widget)
            ->shouldReceive('title')->once()->andReturn($widget)
            ->shouldReceive('link')->once()->andReturnNull();
        $translator->shouldReceive('get')->andReturn('foo');
        $event->shouldReceive('listen')->once()
                ->with('orchestra.ready: admin', 'Orchestra\Foundation\AdminMenuHandler')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.started', array($memoryProvider))->andReturnNull();
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);

        return $app;
    }

    /**
     * Get un-installable mocks setup
     *
     * @return \Mockery
     */
    private function getUnInstallableContainerSetup()
    {
        $app = $this->app;
        $app['env'] = 'production';
        $app['orchestra.installed'] = false;
        $app['orchestra.acl'] = $acl = m::mock('\Orchestra\Auth\Acl\Container');
        $app['orchestra.mail'] = $mailer = m::mock('\Orchestra\Notifier\Mailer[attach]');
        $app['orchestra.memory'] = $memory = m::mock('\Orchestra\Memory\MemoryManager[make]');
        $app['orchestra.notifier'] = $notifier = m::mock('\Orchestra\Notifier\NotifierManager[setDefaultDriver]');
        $app['orchestra.widget'] = $widget = m::mock('\Orchestra\Widget\MenuWidgetHandler');
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository[get,set]');
        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $memoryProvider = m::mock('\Orchestra\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturnNull()
            ->shouldReceive('put')->once()->with('site.name', 'Orchestra Platform')->andReturnNull()
            ->shouldReceive('get')->never()->with('email')->andReturn('memory.email');

        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->never()->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider)
            ->shouldReceive('make')->once()->with('runtime.orchestra')->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add')->once()->with('install')->andReturn($widget)
            ->shouldReceive('title')->once()->with('Install')->andReturn($widget);
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin')
            ->shouldReceive('set')->never()->with('mail', 'memory.email')->andReturnNull();
        $widget->shouldReceive('link')->with('http://localhost/admin/install')->once();

        return $app;
    }

    /**
     * Test Orchestra\Foundation\Application::boot() method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app  = $this->getInstallableContainerSetup();
        $stub = new Application($app);
        $stub->boot();

        $this->assertTrue($app['orchestra.installed']);
        $this->assertEquals($app['orchestra.widget'], $stub->menu());
        $this->assertEquals($app['orchestra.acl'], $stub->acl());
        $this->assertNotEquals($app['orchestra.memory'], $stub->memory());
        $this->assertEquals($stub, $stub->boot());
    }

    /**
     * Test Orchestra\Foundation\Application::boot() method when database
     * is not installed yet.
     *
     * @test
     */
    public function testBootMethodWhenDatabaseIsNotInstalled()
    {
        $app = $this->getUnInstallableContainerSetup();

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
}
