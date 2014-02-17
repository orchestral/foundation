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
        $request = m::mock('\Illuminate\Http\Request');
        $app = new \Illuminate\Foundation\Application($request);

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app = $app);
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
        $app['orchestra.mail'] = $mailer = m::mock('\Orchestra\Notifier\Mailer');
        $app['orchestra.memory'] = $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $app['orchestra.notifier'] = $notifier = m::mock('\Orchestra\Notifier\NotifierManager');
        $app['orchestra.widget'] = $widget = m::mock('\Orchestra\Widget\MenuWidgetHandler');
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository');
        $app['events'] = $event = m::mock('\Illuminate\Events\Dispatcher');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator');
        $request = $app['request'];

        $memoryProvider = m::mock('\Orchestra\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturn('Orchestra');

        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->once()->with($memoryProvider)->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add->title->link')->once()->andReturnNull();
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
        $app['orchestra.mail'] = $mailer = m::mock('\Orchestra\Notifier\Mailer');
        $app['orchestra.memory'] = $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $app['orchestra.notifier'] = $notifier = m::mock('\Orchestra\Notifier\NotifierManager');
        $app['orchestra.widget'] = $widget = m::mock('\Orchestra\Widget\MenuWidgetHandler');
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository');
        $app['events'] = $event = m::mock('\Illuminate\Events\Dispatcher');
        $request = $app['request'];

        $memoryProvider = m::mock('\Orchestra\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturnNull()
            ->shouldReceive('put')->once()->with('site.name', 'Orchestra Platform')->andReturnNull();

        $acl->shouldReceive('make')->once()->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider)
            ->shouldReceive('make')->once()->with('runtime.orchestra')->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('make')->once()->with('menu.app')->andReturn($widget)
            ->shouldReceive('add->title->link')->once()->with('http://localhost/admin/install')->andReturn($widget);
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $event->shouldReceive('fire')->once()->with('orchestra.started', array($memoryProvider))->andReturnNull();

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
        $this->assertTrue($app['orchestra.installed']);
        $this->assertTrue($stub->installed());
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
        $this->assertFalse($stub->installed());
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
