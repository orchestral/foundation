<?php

namespace Orchestra\Foundation\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Application;

class ApplicationTest extends TestCase
{
    /**
     * Test Orchestra\Foundation\Application::registerBaseServiceProviders()
     * method.
     *
     * @test
     */
    public function testRegisterBaseServiceProviders()
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf('\Illuminate\Events\Dispatcher', $app['events']);
        $this->assertInstanceOf('\Orchestra\Routing\Router', $app['router']);
    }

    public function testGettingDeferredServices()
    {
        $app = new Application(__DIR__);

        $this->assertEquals([], $app->getDeferredServices());
    }
}
