<?php

namespace Orchestra\Tests\Unit\Http\Handlers;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Foundation\Http\Handlers\UserMenuHandler;

class UserMenuHandlerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\UserMenuHandler::handle()
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

        $acl->shouldReceive('canIf')->with('manage-users')->once()->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.users.list')->andReturn('users');
        $foundation->shouldReceive('handles')->once()->with('orchestra::users')->andReturn('admin/users');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('users')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/users')->andReturnSelf()
            ->shouldReceive('handles')->once()->with('orchestra::users')->andReturnNull();

        $stub = new UserMenuHandler($app);
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
        $app = new Container();
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $acl->shouldReceive('canIf')->with('manage-users')->once()->andReturn(false);

        $stub = new UserMenuHandler($app);
        $this->assertNull($stub->handle());
    }
}
