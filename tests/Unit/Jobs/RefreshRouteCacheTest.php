<?php

namespace Orchestra\Tests\Unit\Jobs;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Jobs\RefreshRouteCache;

class RefreshRouteCacheTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_refresh_cached_route()
    {
        $app = m::mock('\Illuminate\Contracts\Foundation\Application');
        $kernel = m::mock('\Illuminate\Contracts\Console\Kernel');

        $app->shouldReceive('routesAreCached')->once()->andReturn(true)
            ->shouldReceive('terminating')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($callback) {
                    $callback();
                });

        $kernel->shouldReceive('call')->once()->with('route:cache')->andReturnNull();

        $stub = new RefreshRouteCache();

        $this->assertNull($stub->handle($app, $kernel));
    }
}
