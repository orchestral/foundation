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
    protected function tearDown()
    {
        m::close();
    }

    public function testHandleMethod()
    {
        $app = m::mock('\Illuminate\Contracts\Foundation\Application');
        $kernel = m::mock('\Illuminate\Contracts\Console\Kernel');

        $app->shouldReceive('routesAreCached')->once()->andReturn(true)
            ->shouldReceive('terminating')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) {
                    $c();
                });

        $kernel->shouldReceive('call')->once()->with('route:cache')->andReturnNull();

        $stub = new RefreshRouteCache();

        $this->assertNull($stub->handle($app, $kernel));
    }
}
