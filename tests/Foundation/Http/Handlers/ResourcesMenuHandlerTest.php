<?php namespace Orchestra\Foundation\Http\Handlers\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Illuminate\Container\Container;
use Orchestra\Foundation\Http\Handlers\ResourcesMenuHandler;

class ResourcesMenuHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ResourcesMenuHandler::handle()
     * method with resources.
     *
     * @test
     */
    public function testCreatingMenuWithResources()
    {
        $app = new Container();
        $app['orchestra.resources'] = $resources = m::mock('\Orchestra\Resources\Factory');
        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['translator'] = $translator = m::mock('\Illuminate\Translator\Translator');

        $foo = new Fluent([
            'name' => 'Foo',
            'visible' => true,
        ]);

        $bar = new Fluent([
            'name' => 'Bar',
            'visible' => false,
        ]);

        $resources->shouldReceive('all')->once()->andReturn(compact('foo', 'bar'));

        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.resources.list')->andReturn('resources');
        $foundation->shouldReceive('handles')->once()->with('orchestra::resources')->andReturn('admin/resources');
        $menu->shouldReceive('add')->once()->with('resources', '>:extensions')->andReturnSelf()
            ->shouldReceive('title')->once()->with('resources')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/resources')->andReturnNull();

        $foundation->shouldReceive('handles')->once()->with('orchestra::resources/foo')->andReturn('foo-resource');
        $menu->shouldReceive('add')->once()->with('foo', '^:resources')->andReturnSelf()
            ->shouldReceive('title')->once()->with('Foo')->andReturnSelf()
            ->shouldReceive('link')->once()->with('foo-resource')->andReturnNull();

        $stub = new ResourcesMenuHandler($app);
        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ResourcesMenuHandler::handle()
     * method without resources.
     *
     * @test
     */
    public function testCreatingMenuWithoutResources()
    {
        $app = new Container();
        $app['orchestra.resources'] = $resources = m::mock('\Orchestra\Resources\Factory');
        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['translator'] = $translator = m::mock('\Illuminate\Translator\Translator');

        $foo = new Fluent([
            'name' => 'Foo',
            'visible' => false,
        ]);

        $bar = new Fluent([
            'name' => 'Bar',
            'visible' => false,
        ]);

        $resources->shouldReceive('all')->once()->andReturn(compact('foo', 'bar'));

        $stub = new ResourcesMenuHandler($app);
        $this->assertTrue($stub->authorize());
        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ResourcesMenuHandler::handle()
     * method without `orchestra.resources` bound to container.
     *
     * @test
     */
    public function testCreatingMenuWithoutBoundDependencies()
    {
        $app = new Container();
        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['translator'] = $translator = m::mock('\Illuminate\Translator\Translator');

        $stub = new ResourcesMenuHandler($app);
        $this->assertFalse($stub->authorize());
        $this->assertNull($stub->handle());
    }
}
