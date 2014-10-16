<?php namespace Orchestra\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;

class HelpersTest extends \PHPUnit_Framework_TestCase
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

        $this->app['translator'] = $trans = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $this->app['orchestra.app'] = $orchestra = m::mock('\Orchestra\Foundation\Foundation')->makePartial();

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);

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
        $this->assertInstanceOf('\Orchestra\Foundation\Foundation', orchestra());
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
}
