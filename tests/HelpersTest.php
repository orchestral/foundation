<?php namespace Orchestra\TestCase;

use Illuminate\Container\Container;
use Mockery as m;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;

class HelpersTest extends \PHPUnit_Framework_TestCase
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
        $this->app = new Application(__DIR__);

        $this->app['translator'] = $trans = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $this->app['orchestra.app'] = $orchestra = m::mock('\Orchestra\Foundation\Foundation')->makePartial();

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
        Container::setInstance($this->app);

        $trans->shouldReceive('trans')->andReturn('translated');
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);

        m::close();
    }

    /**
     * Test orchestra() method.
     *
     * @test
     */
    public function testOrchestraMethod()
    {
        $this->app['orchestra.platform.memory'] = m::mock('\Orchestra\Memory\Provider');

        $this->assertInstanceOf('\Orchestra\Foundation\Foundation', orchestra());
        $this->assertInstanceOf('\Orchestra\Memory\Provider', orchestra('memory'));
    }

    /**
     * Test memorize() method.
     *
     * @test
     */
    public function testMemorizeMethod()
    {
        $this->app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Memory\Provider')->makePartial();

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

        $orchestra->shouldReceive('handles')->once()->with('app::foo')->andReturn('foo');

        $this->assertEquals('foo', handles('app::foo'));
    }

    /**
     * Test resources() method.
     *
     * @test
     */
    public function testResourcesMethod()
    {
        $orchestra = $this->app['orchestra.app'];

        $orchestra->shouldReceive('handles')->once()
            ->with('orchestra/foundation::resources/foo')->andReturn('foo');

        $this->assertEquals('foo', resources('foo'));
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
