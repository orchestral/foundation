<?php

namespace Orchestra\Tests\Feature\Http\Handlers;

use Illuminate\Container\Container;
use Mockery as m;
use Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler;
use Orchestra\Testing\TestCase;

class ExtensionMenuHandlerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithAuthorizedUser()
    {
        $this->instance('orchestra.app', $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation'));
        $this->instance('orchestra.extension', $extension = m::mock('\Orchestra\Contracts\Extension\Factory'));
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));
        $this->instance('orchestra.platform.menu', $menu = m::mock('\Orchestra\Widget\Handlers\Menu'));
        $this->instance('translator', $translator = m::mock('\Illuminate\Translator\Translator'));

        $acl->shouldReceive('canIf')->with('manage-orchestra')->once()->andReturn(true);
        $translator->shouldReceive('get')->once()->with('orchestra/foundation::title.extensions.list', [], null)->andReturn('extensions');
        $foundation->shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('admin/extensions');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('extensions')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/extensions')->andReturnSelf()
            ->shouldReceive('handles')->once()->with('orchestra::extensions')->andReturnNull();

        $stub = new ExtensionMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method without authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithoutAuthorizedUser()
    {
        $this->instance('orchestra.platform.acl', $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization'));

        $acl->shouldReceive('canIf')->with('manage-orchestra')->once()->andReturn(false);

        $stub = new ExtensionMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method without `orchestra.extension` bound to container.
     *
     * @test
     */
    public function testCreatingMenuWithoutBoundDependencies()
    {
        $stub = new ExtensionMenuHandler($this->app);

        $this->assertNull($stub->handle());
    }
}
