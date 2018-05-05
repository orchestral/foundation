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

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Extension::register('acme/acl', realpath(__DIR__.'/../../extensions/acme/acl'));
        Extension::register('acme/cms', realpath(__DIR__.'/../../extensions/acme/cms'));

        Extension::detect();
    }

    /** @test */
    public function it_can_activate_extension()
    {
        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/extensions/acme/cms/activate')
            ->seePageIs('admin/extensions')
            ->seeText('Extension acme/cms activated');
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
            ->makeRequest('POST', 'admin/extensions/acme/cms/activate')
            ->seePageIs('admin/publisher/ftp');
    }
}
