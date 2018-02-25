<?php

namespace Orchestra\Tests\Feature\Providers;

use Orchestra\Tests\Feature\TestCase;

class FoundationServiceProviderTest extends TestCase
{
    /**
     * Test event are registered.
     *
     * @test
     */
    public function it_register_proper_services()
    {
        $this->assertFalse($this->app['orchestra.installed']);
        $this->assertInstanceOf('\Orchestra\Foundation\Foundation', $this->app['orchestra.app']);
    }
}
