<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
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
        $app                            = new Container();
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');

        $stub = new AdminMenuHandler($app);
        $stub->handle();
    }
}
