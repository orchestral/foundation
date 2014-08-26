<?php namespace Orchestra\Foundation\Abstractable\TestCase;

use Mockery as m;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Abstractable\RouteManager;

class RouteManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    private $app = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Application;
        $_SERVER['RouteManagerTest@callback'] = null;

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        unset($_SERVER['RouteManagerTest@callback']);
        m::close();
    }

    /**
     * Installed setup.
     *
     */
    private function getApplicationMocks()
    {
        $app = $this->app;
        $app['request'] = $request = m::mock('\Illuminate\Http\Request');

        $request->shouldReceive('root')->andReturn('http://localhost')
            ->shouldReceive('secure')->andReturn(false);

        return $app;
    }

    /**
     * Test Orchestra\Foundation\RouteManager::group() method.
     *
     * @test
     */
    public function testGroupMethod()
    {
        $app  = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();

        $config->shouldReceive('get')->once()
            ->with('orchestra/foundation::handles', 'admin')->andReturn('admin');

        $stub = new StubRouteManager($app);

        $expected = array(
            'before' => 'auth',
            'prefix' => 'admin',
            'domain' => null,
        );

        $this->assertEquals($expected, $stub->group('orchestra', 'admin', array('before' => 'auth')));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::group() method
     * with closure.
     *
     * @test
     */
    public function testGroupMethodWithClosure()
    {
        $app  = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $app['router'] = $router = m::mock('\Illuminate\Routing\Router');

        $group = array(
            'before' => 'auth',
            'prefix' => 'admin',
            'domain' => null,
        );

        $callback = function () { };

        $config->shouldReceive('get')->once()
            ->with('orchestra/foundation::handles', 'admin')->andReturn('admin');
        $router->shouldReceive('group')->once()->with($group, $callback)->andReturnNull();

        $stub = new StubRouteManager($app);

        $this->assertEquals($group, $stub->group('orchestra', 'admin', array('before' => 'auth'), $callback));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::group() method
     * with closure and not array.
     *
     * @test
     */
    public function testGroupMethodWithClosureAndNotArray()
    {
        $app  = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $app['router'] = $router = m::mock('\Illuminate\Routing\Router');

        $group = array(
            'prefix' => 'admin',
            'domain' => null,
        );

        $callback = function () { };

        $config->shouldReceive('get')->once()
            ->with('orchestra/foundation::handles', 'admin')->andReturn('admin');
        $router->shouldReceive('group')->once()->with($group, $callback)->andReturnNull();

        $stub = new StubRouteManager($app);

        $this->assertEquals($group, $stub->group('orchestra', 'admin', $callback));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::handles() method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory')->makePartial();
        $app['url'] = $url = m::mock('\Illuminate\Routing\UrlGenerator')->makePartial();

        $appRoute = m::mock('\Orchestra\Extension\RouteGenerator')->makePartial();

        $config->shouldReceive('get')->once()
            ->with('orchestra/foundation::handles', '/')->andReturn('admin');

        $appRoute->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);
        $url->shouldReceive('to')->once()->with('/')->andReturn('/')
            ->shouldReceive('to')->once()->with('info?foo=bar')->andReturn('info?foo=bar');

        $stub = new StubRouteManager($app);

        $this->assertEquals('/', $stub->handles('app::/'));
        $this->assertEquals('info?foo=bar', $stub->handles('info?foo=bar'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer'));
        $this->assertEquals('http://localhost/admin/installer', $stub->handles('orchestra::installer/'));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::is() method.
     *
     * @test
     */
    public function testIsMethod()
    {
        $app = $this->getApplicationMocks();
        $request = $app['request'];
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory')->makePartial();
        $app['url'] = $url = m::mock('\Illuminate\Routing\UrlGenerator')->makePartial();

        $appRoute = m::mock('\Orchestra\Extension\RouteGenerator')->makePartial();

        $config->shouldReceive('get')->once()
            ->with('orchestra/foundation::handles', '/')->andReturn('admin');
        $request->shouldReceive('path')->twice()->andReturn('/');
        $appRoute->shouldReceive('is')->once()->with('/')->andReturn(true)
            ->shouldReceive('is')->once()->with('info?foo=bar')->andReturn(true);
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);

        $stub = new StubRouteManager($app);

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
        $app  = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository');

        $stub = m::mock('\Orchestra\Foundation\Abstractable\RouteManager[group]', [$app]);

        $closure = function () {

        };

        $stub->shouldReceive('group')->once()->with('orchestra/foundation', 'orchestra', [], $closure)->andReturn([]);

        $this->assertNull($stub->namespaced($closure));
    }

    /**
     * Test Orchestra\Foundation\RouteManager::when() method.
     *
     * @test
     */
    public function testWhenMethod()
    {
        $app = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository')->makePartial();
        $app['events'] = $events = m::mock('\Illuminate\Events\Dispatcher')->makePartial();
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory')->makePartial();
        $app['url'] = $url = m::mock('\Illuminate\Routing\UrlGenerator')->makePartial();

        $appRoute = m::mock('\Orchestra\Extension\RouteGenerator');

        $appRoute->shouldReceive('is')->once()->with('/')->andReturn(true)
            ->shouldReceive('is')->once()->with('foo')->andReturn(false);
        $extension->shouldReceive('route')->once()->with('app', '/')->andReturn($appRoute);
        $events->shouldReceive('makeListener')->twice()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) {
                    return $c;
                });

        $stub = new StubRouteManager($app);

        $this->assertNull($_SERVER['RouteManagerTest@callback']);

        $stub->when('app::/', function () {
            $_SERVER['RouteManagerTest@callback'] = 'app::/';
        });

        $app->boot();

        $this->assertEquals('app::/', $_SERVER['RouteManagerTest@callback']);

        $stub->when('app::foo', function () {
            $_SERVER['RouteManagerTest@callback'] = 'app::foo';
        });

        $app->boot();

        $this->assertNotEquals('app::foo', $_SERVER['RouteManagerTest@callback']);
    }
}

class StubRouteManager extends RouteManager
{
    public function boot()
    {
        //
    }
}
