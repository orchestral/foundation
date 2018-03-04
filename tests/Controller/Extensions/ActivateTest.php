<?php

namespace Orchestra\Tests\Controller\Extensions;

use Mockery as m;
use Orchestra\Support\Facades\Extension;
use Orchestra\Tests\Controller\TestCase;
use Orchestra\Extension\Processor\Activator;
use Orchestra\Foundation\Testing\Installation;

class ActivateTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_activate_extension()
    {
        $this->app->make('orchestra.extension.finder')->addPath(__DIR__.'/../../extensions/');

        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/extensions/acme/story/activate')
            ->seePageIs('admin/extensions');
    }

    /** @test */
    public function it_can_activate_extension_while_requires_asset_publishing()
    {
        $this->instance('orchestra.publisher.ftp', $client = m::mock('\Orchestra\Contracts\Publisher\Uploader'));
        $client->shouldReceive('connected')->once()->andReturn(false);

        $this->instance(Activator::class, $activator = m::mock(Activator::class.'[execute]', [
            $this->app->make(\Orchestra\Contracts\Extension\Factory::class),
        ]))->shouldAllowMockingProtectedMethods();

        $activator->shouldReceive('execute')
            ->with(
                m::type('\Orchestra\Contracts\Extension\Listener\Activator'), 'activation', m::any(), m::type('\Closure')
            )->andReturnUsing(function ($listener, $type, $extension) {
                return $listener->activationHasFailed($extension, []);
            });

        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/extensions/acme/story/activate')
            ->seePageIs('admin/publisher/ftp');
    }
}
