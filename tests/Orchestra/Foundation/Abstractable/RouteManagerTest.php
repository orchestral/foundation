<?php namespace Orchestra\Foundation\Abstractable\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;

class RouteManagerTest extends \PHPUnit_Framework_TestCase
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
        $app['config'] = $config = m::mock('Config\Manager');

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
     * Test Orchestra\Foundation\RouteManager::handles() method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = $this->getApplicationMocks();
        $app['config'] = $config = m::mock('Config\Manager');
        $app['orchestra.extension'] = $extension = m::mock('Extension');
        $app['url'] = $url = m::mock('Url');

        $appRoute = m::mock('\Orchestra\Extension\RouteGenerator');

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
}

class StubRouteManager extends \Orchestra\Foundation\Abstractable\RouteManager
{
    public function boot()
    {
        //
    }
}
