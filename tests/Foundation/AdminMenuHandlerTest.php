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
    public function testHandleMethodWithResources()
    {
        $app = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $resources = m::mock('\Orchestra\Resources\Factory')->makePartial();
        $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $app->shouldReceive('bound')->once()->with('orchestra.resources')->andReturn(true)
            ->shouldReceive('make')->once()->with('orchestra.resources')->andReturn($resources)
            ->shouldReceive('acl')->once()->andReturn($acl)
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

        $stub = new AdminMenuHandler($app, $translator);
        $stub->handle();
    }

    /**
     * Test Orchestra\Foundation\AdminMenuHandler::handle()
     * method.
     *
     * @test
     */
    public function testHandleMethodWithoutResources()
    {
        $app = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();

        $app->shouldReceive('bound')->once()->with('orchestra.resources')->andReturn(false)
            ->shouldReceive('acl')->once()->andReturn($acl)
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

        $stub = new AdminMenuHandler($app, $translator);
        $stub->handle();
    }
}
