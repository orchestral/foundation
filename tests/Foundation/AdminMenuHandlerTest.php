<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\AdminMenuHandler;

class AdminMenuHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\AdminMenuHandler::handle()
     * method.
     *
     * @test
     */
    public function testHandleMethod()
    {
        $app = m::mock('\Orchestra\Foundation\Foundation')->makePartial();
        $acl = m::mock('\Orchestra\Auth\Acl\Container');
        $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $resources = m::mock('\Orchestra\Resources\Factory')->makePartial();
        $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $app->shouldReceive('acl')->once()->andReturn($acl)
            ->shouldReceive('menu')->once()->andReturn($menu)
            ->shouldReceive('bound')->once()->with('orchestra.extension')->andReturn(true);

        $acl->shouldReceive('can')->once()->with('manage-users')->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.users.list')->andReturn('user');
        $app->shouldReceive('handles')->once()->with('orchestra::users')->andReturn('user');
        $menu->shouldReceive('add')->once()->with('users')->andReturn($menu)
            ->shouldReceive('title')->once()->with('user')->andReturn($menu)
            ->shouldReceive('link')->once()->with('user')->andReturnNull();

        $acl->shouldReceive('can')->once()->with('manage-orchestra')->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.extensions.list')->andReturn('extension');
        $app->shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('extension');
        $menu->shouldReceive('add')->once()->with('extensions', '>:home')->andReturn($menu)
            ->shouldReceive('title')->once()->with('extension')->andReturn($menu)
            ->shouldReceive('link')->once()->with('extension')->andReturnNull();

        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.settings.list')->andReturn('setting');
        $app->shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('setting');
        $menu->shouldReceive('add')->once()->with('settings')->andReturn($menu)
            ->shouldReceive('title')->once()->with('setting')->andReturn($menu)
            ->shouldReceive('link')->once()->with('setting')->andReturnNull();

        $foo = new Fluent(array(
            'name'    => 'Foo',
            'visible' => true,
        ));

        $bar = new Fluent(array(
            'name'    => 'Bar',
            'visible' => false,
        ));

        $resources->shouldReceive('all')->once()->andReturn(array('foo' => $foo, 'bar' => $bar));

        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.resources.list')->andReturn('resource');
        $app->shouldReceive('handles')->once()->with('orchestra::resources')->andReturn('resource');
        $menu->shouldReceive('add')->once()->with('resources', '>:extensions')->andReturn($menu)
            ->shouldReceive('title')->once()->with('resource')->andReturn($menu)
            ->shouldReceive('link')->once()->with('resource')->andReturnNull();

        $app->shouldReceive('handles')->once()->with('orchestra::resources/foo')->andReturn('foo-resource');
        $menu->shouldReceive('add')->once()->with('foo', '^:resources')->andReturn($menu)
            ->shouldReceive('title')->once()->with('Foo')->andReturn($menu)
            ->shouldReceive('link')->once()->with('foo-resource')->andReturnNull();

        $stub = new AdminMenuHandler($app, $resources, $translator);
        $stub->handle();
    }
}
