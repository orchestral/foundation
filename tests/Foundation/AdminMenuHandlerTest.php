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
    public function testCreatingMenu()
    {
        $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');
        $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $translator = m::mock('\Illuminate\Translation\Translator');

        $foundation->shouldReceive('acl')->once()->andReturn($acl)
            ->shouldReceive('menu')->once()->andReturn($menu);

        $stub = new AdminMenuHandler($foundation, $translator);
        $stub->handle();
    }
}
