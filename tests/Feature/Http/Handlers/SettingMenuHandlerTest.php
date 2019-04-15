<?php

namespace Orchestra\Tests\Feature\Http\Handlers;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Foundation\Http\Handlers\SettingMenuHandler;

class SettingMenuHandlerTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Http\Handlers\SettingMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithAuthorizedUser()
    {
        $this->instance('orchestra.app', $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation'));
        $this->instance('orchestra.platform.menu', $menu = m::mock('\Orchestra\Widget\Handlers\Menu'));
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));
        $this->instance('translator', $translator = m::mock('\Illuminate\Translator\Translator'));

        $acl->shouldReceive('canIf')->with('manage-orchestra')->once()->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.settings.list', [], null)->andReturn('settings');
        $foundation->shouldReceive('handles')->once()->with('orchestra::settings')->andReturn('admin/settings');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('settings')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/settings')->andReturnSelf()
            ->shouldReceive('handles')->once()->with('orchestra::settings')->andReturnNull();

        $stub = new SettingMenuHandler($this->app);

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
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));

        $acl->shouldReceive('canIf')->with('manage-orchestra')->once()->andReturn(false);

        $stub = new SettingMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }
}
