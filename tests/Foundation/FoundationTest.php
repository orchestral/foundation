<?php

namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Foundation;
use Orchestra\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\RouteResolver;

class FoundationTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    private $app;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $app = new Application(__DIR__);

        $app['config'] = m::mock('\Illuminate\Contracts\Config\Repository');
        $app['encrypter'] = m::mock('\Illuminate\Encryption\Encrypter');
        $app['events'] = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $app['translator'] = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $app['url'] = m::mock('\Illuminate\Routing\UrlGenerator')->makePartial();
        $app['orchestra.acl'] = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $app['orchestra.extension'] = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.extension.status'] = m::mock('\Orchestra\Contracts\Extension\StatusChecker');
        $app['orchestra.mail'] = m::mock('\Orchestra\Notifier\Mailer')->makePartial();
        $app['encrypter'] = m::mock('\Illuminate\Contracts\Encryption\Encrypter');
        $app['orchestra.memory'] = m::mock('\Orchestra\Memory\MemoryManager', [$app]);
        $app['orchestra.notifier'] = m::mock('\Orchestra\Notifier\NotifierManager', [$app]);
        $app['orchestra.widget'] = m::mock('\Orchestra\Widget\Handlers\Menu');

        Facade::clearResolvedInstances();
        Application::setInstance($app);

        $this->app = $app;
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Get installable mocks setup.
     *
     * @return \Mockery
     */
    private function getInstallableContainerSetup()
    {
        $app = $this->app;
        $request = m::mock('\Illuminate\Http\Request');
        $acl = $app['orchestra.acl'];
        $config = $app['config'];
        $event = $app['events'];
        $mailer = $app['orchestra.mail'];
        $memory = $app['orchestra.memory'];
        $notifier = $app['orchestra.notifier'];
        $status = $app['orchestra.extension.status'];
        $translator = $app['translator'];
        $widget = $app['orchestra.widget'];

        $app['env'] = 'production';
        $app['orchestra.installed'] = false;
        $app['request'] = $request;

        $memoryProvider = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturn('Orchestra');
        $acl->shouldReceive('make')->once()->andReturn($acl)
            ->shouldReceive('attach')->once()->with($memoryProvider)->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('add->title->link->icon')->once()->with('home')->andReturnNull();
        $translator->shouldReceive('get')->andReturn('foo');
        $event->shouldReceive('listen')->once()
                ->with('orchestra.started: admin', 'Orchestra\Foundation\Http\Handlers\UserMenuHandler')->andReturnNull()
            ->shouldReceive('listen')->once()
                ->with('orchestra.started: admin', 'Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler')->andReturnNull()
            ->shouldReceive('listen')->once()
                ->with('orchestra.started: admin', 'Orchestra\Foundation\Http\Handlers\SettingMenuHandler')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.started', [$memoryProvider])->andReturnNull();
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin')
            ->shouldReceive('set')->once()->with('app.name', 'Orchestra')->andReturnNull();
        $status->shouldReceive('mode')->once()->andReturn('normal');
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http');

        return $app;
    }

    /**
     * Get un-installable mocks setup.
     *
     * @return \Mockery
     */
    private function getUnInstallableContainerSetup()
    {
        $app = $this->app;
        $request = m::mock('\Illuminate\Http\Request');
        $acl = $app['orchestra.acl'];
        $config = $app['config'];
        $event = $app['events'];
        $mailer = $app['orchestra.mail'];
        $memory = $app['orchestra.memory'];
        $notifier = $app['orchestra.notifier'];
        $status = $app['orchestra.extension.status'];
        $widget = $app['orchestra.widget'];

        $app['env'] = 'production';
        $app['request'] = $request;
        $app['orchestra.installed'] = false;

        $memoryProvider = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memoryProvider->shouldReceive('get')->once()->with('site.name')->andReturnNull()
            ->shouldReceive('put')->once()->with('site.name', 'Orchestra Platform')->andReturnNull();
        $acl->shouldReceive('make')->once()->andReturn($acl);
        $mailer->shouldReceive('attach')->once()->with($memoryProvider)->andReturnNull();
        $memory->shouldReceive('make')->once()->andReturn($memoryProvider)
            ->shouldReceive('make')->once()->with('runtime.orchestra')->andReturn($memoryProvider);
        $notifier->shouldReceive('setDefaultDriver')->once()->with('orchestra')->andReturnNull();
        $widget->shouldReceive('make')->once()->with('menu.orchestra')->andReturn($widget)
            ->shouldReceive('add->title->link->icon')->once()->with('power-off')->andReturn($widget);
        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http');
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $status->shouldReceive('mode')->once()->andReturn('normal');
        $event->shouldReceive('fire')->once()->with('orchestra.started', [$memoryProvider])->andReturnNull();

        return $app;
    }

    /**
     * Test Orchestra\Foundation\Foundation::boot() method.
     *
     * @test
     * @group foo
     */
    public function testBootMethod()
    {
        $app = $this->getInstallableContainerSetup();
        $stub = new Foundation($app, new RouteResolver($app));
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
     * Test Orchestra\Foundation\Foundation::boot() method when database
     * is not installed yet.
     *
     * @test
     */
    public function testBootMethodWhenDatabaseIsNotInstalled()
    {
        $app = $this->getUnInstallableContainerSetup();

        $stub = new Foundation($app, new RouteResolver($app));
        $stub->boot();

        $this->assertFalse($app['orchestra.installed']);
        $this->assertFalse($stub->installed());
    }

    /**
     * Test Orchestra\Foundation\RouteManager::handles() method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = $this->app;
        $config = $app['config'];
        $extension = $app['orchestra.extension'];
        $status = $app['orchestra.extension.status'];
        $url = $app['url'];

        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $appRoute = m::mock('\Orchestra\Contracts\Extension\RouteGenerator');

        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http');
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $appRoute->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);
        $status->shouldReceive('mode')->times(4)->andReturn('normal');
        $url->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');

        $stub = new StubRouteManager($app, new RouteResolver($app));

        $this->assertEquals('/', $stub->handles('app::/'));
        $this->assertEquals('info?foo=bar', $stub->handles('info?foo=bar'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer/'));
    }

    /**
     * Test Orchestra\Foundation\Foundation::is() method.
     *
     * @test
     */
    public function testIsMethod()
    {
        $app = $this->app;
        $config = $app['config'];
        $extension = $app['orchestra.extension'];
        $status = $app['orchestra.extension.status'];

        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $appRoute = m::mock('\Orchestra\Contracts\Extension\RouteGenerator');

        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('getScheme')->andReturn('http');
        $config->shouldReceive('get')->once()->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $request->shouldReceive('path')->twice()->andReturn('/');
        $appRoute->shouldReceive('is')->once()->with('/')->andReturn(true)
            ->shouldReceive('is')->once()->with('info?foo=bar')->andReturn(true);
        $status->shouldReceive('mode')->times(4)->andReturn('normal');
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);

        $stub = new StubRouteManager($app, new RouteResolver($app));

        $this->assertTrue($stub->is('app::/'));
        $this->assertTrue($stub->is('info?foo=bar'));
        $this->assertFalse($stub->is('orchestra::login'));
        $this->assertFalse($stub->is('orchestra::login'));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::namespaced() method.
     *
     * @test
     */
    public function testNamespacedMethod()
    {
        $stub = m::mock('\Orchestra\Foundation\Foundation[group]', [
            $this->app, new RouteResolver($this->app),
        ]);

        $closure = function () {
        };

        $middleware = ['orchestra'];

        $stub->shouldReceive('group')->times(3)
            ->with('orchestra/foundation', 'orchestra', ['middleware' => $middleware], $closure)
            ->andReturn([]);
        $stub->shouldReceive('group')->once()
            ->with('orchestra/foundation', 'orchestra', ['namespace' => 'Foo', 'middleware' => $middleware], $closure)
            ->andReturn([]);

        $this->assertNull($stub->namespaced('', $closure));
        $this->assertNull($stub->namespaced('\\', $closure));
        $this->assertNull($stub->namespaced(null, $closure));
        $this->assertNull($stub->namespaced('Foo', $closure));
    }
}

class StubRouteManager extends Foundation
{
    public function boot()
    {
        //
    }
}
