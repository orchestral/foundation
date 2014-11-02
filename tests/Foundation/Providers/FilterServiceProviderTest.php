<?php namespace Orchestra\Foundation\Providers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\Providers\FilterServiceProvider;

class FilterServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    public function testBootMethod()
    {
        $app = new Container;

        $router = $app['router'] = m::mock('\Illuminate\Routing\Router');

        $router->shouldReceive('before')->once()->with('BeforeFilter')->andReturnNull();
        $router->shouldReceive('after')->once()->with('AfterFilter')->andReturnNull();

        $stub = new StubFilterProvider($app);

        $stub->boot();
    }
}

class StubFilterProvider extends FilterServiceProvider
{
    protected $before = ['BeforeFilter'];
    protected $after = ['AfterFilter'];
}