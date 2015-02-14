<?php namespace Orchestra\Foundation\Http\Handlers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\Http\Handlers\SettingMenuHandler;

class SettingMenuHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Foundation\Http\Handlers\SettingMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithAuthorizedUser()
    {
        $app = new Container();
        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['translator'] = $translator = m::mock('\Illuminate\Translator\Translator');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $acl->shouldReceive('can')->with('manage-orchestra')->once()->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.settings.list')->andReturn('settings');
        $foundation->shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('admin/settings');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('settings')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/settings')->andReturnNull();

        $stub = new SettingMenuHandler($app);
        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\SettingMenuHandler::handle()
     * method without authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithoutAuthorizedUser()
    {
        $app = new Container();
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $acl->shouldReceive('can')->with('manage-orchestra')->once()->andReturn(false);

        $stub = new SettingMenuHandler($app);
        $this->assertNull($stub->handle());
    }
}
