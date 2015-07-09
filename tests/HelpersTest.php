<?php namespace Orchestra\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    private $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Application(__DIR__);

        $this->app['translator']    = $trans    = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $this->app['orchestra.app'] = $orchestra = m::mock('\Orchestra\Contracts\Foundation\Foundation');

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);

        $trans->shouldReceive('trans')->andReturn('translated');
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        Facade::clearResolvedInstances();

        m::close();
    }

    /**
     * Test orchestra() method.
     *
     * @test
     */
    public function testOrchestraMethod()
    {
        $this->app['orchestra.platform.memory'] = m::mock('\Orchestra\Contracts\Memory\Provider');

        $this->assertInstanceOf('\Orchestra\Contracts\Foundation\Foundation', orchestra());
        $this->assertInstanceOf('\Orchestra\Contracts\Memory\Provider', orchestra('memory'));
    }

    /**
     * Test memorize() method.
     *
     * @test
     */
    public function testMemorizeMethod()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('site.name', null)->andReturn('Orchestra');

        $this->assertEquals('Orchestra', memorize('site.name'));
    }

    /**
     * Test handles() method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $orchestra = $this->app['orchestra.app'];

        $orchestra->shouldReceive('handles')->once()->with('app::foo', [])->andReturn('foo');

        $this->assertEquals('foo', handles('app::foo'));
    }

    /**
     * Test get_meta() method.
     *
     * @test
     */
    public function testGetMetaMethod()
    {
        $this->app['orchestra.meta'] = $meta = m::mock('\Orchestra\Foundation\Meta');

        $meta->shouldReceive('get')->once()->with('title', 'foo')->andReturn('foobar');

        $this->assertEquals('foobar', get_meta('title', 'foo'));
    }

    /**
     * Test set_meta() method.
     *
     * @test
     */
    public function testSetMetaMethod()
    {
        $this->app['orchestra.meta'] = $meta = m::mock('\Orchestra\Foundation\Meta');

        $meta->shouldReceive('set')->once()->with('title', 'foo')->andReturnNull();

        $this->assertNull(set_meta('title', 'foo'));
    }
}
