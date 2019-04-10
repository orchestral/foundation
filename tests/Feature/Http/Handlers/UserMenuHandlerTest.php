<?php

namespace Orchestra\Tests\Feature\Http\Handlers;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Foundation\Http\Handlers\UserMenuHandler;

class UserMenuHandlerTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Http\Handlers\UserMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithAuthorizedUser()
    {
        $this->instance('orchestra.app', $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation'));
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));
        $this->instance('orchestra.platform.menu', $menu = m::mock('\Orchestra\Widget\Handlers\Menu'));
        $this->instance('translator', $translator = m::mock('\Illuminate\Translator\Translator'));

        $acl->shouldReceive('canIf')->with('manage-users')->once()->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.users.list', [], null)->andReturn('users');
        $foundation->shouldReceive('handles')->once()->with('orchestra::users')->andReturn('admin/users');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('users')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/users')->andReturnSelf()
            ->shouldReceive('handles')->once()->with('orchestra::users')->andReturnNull();

        $stub = new UserMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\UserMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithoutAuthorizedUser()
    {
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));

        $acl->shouldReceive('canIf')->with('manage-users')->once()->andReturn(false);

        $stub = new UserMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }
}
