<?php

namespace Orchestra\Tests\Feature;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_can_resolve_orchestra_publisher()
    {
        $this->assertInstanceOf(
            '\Orchestra\Foundation\Publisher\PublisherManager', $this->app->make('orchestra.publisher')
        );
    }

    /** @test */
    public function it_can_resolve_eloquent_models()
    {
        $this->assertInstanceOf(
            '\Orchestra\Model\Role', $this->app->make('orchestra.role')
        );

        $this->assertInstanceOf(
            '\Orchestra\Model\User', $this->app->make('orchestra.user')
        );
    }

    /** @test */
    public function it_can_resolve_config_cache_command()
    {
        $this->assertInstanceOf(
            '\Orchestra\Config\Console\ConfigCacheCommand', $this->app['command.config.cache']
        );
    }
}
