<?php

namespace Orchestra\Tests\Feature\Bootstrap;

use Mockery as m;
use Orchestra\Foundation\Bootstrap\LoadFoundation;
use Orchestra\Tests\Feature\TestCase;

class LoadFoundationTest extends TestCase
{
    /** @test */
    public function it_can_bootstrap_foundation()
    {
        $this->instance('orchestra.app', $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation'));

        $foundation->shouldReceive('boot')->once()->andReturnSelf();

        $this->assertNull((new LoadFoundation())->bootstrap($this->app));
    }
}
