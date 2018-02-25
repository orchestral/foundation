<?php

namespace Orchestra\Tests\Feature;

class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function it_registes_base_service_providers()
    {
        $this->assertInstanceOf('\Illuminate\Events\Dispatcher', $this->app['events']);
        $this->assertInstanceOf('\Orchestra\Routing\Router', $this->app['router']);
    }

    /** @test */
    public function it_can_get_deferred_services()
    {
        $this->assertEquals([], $this->app->getDeferredServices());
    }
}
