<?php

namespace Orchestra\Tests\Unit\Providers;

use Mockery as m;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Providers\BasicThrottle;
use Orchestra\Foundation\Providers\FoundationServiceProvider;

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
