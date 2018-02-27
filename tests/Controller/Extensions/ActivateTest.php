<?php

namespace Orchestra\Tests\Controller\Extensions;

use Orchestra\Support\Facades\Extension;
use Orchestra\Tests\Controller\TestCase;
use Orchestra\Foundation\Testing\Installation;

class ActivateTest extends TestCase
{
    use Installation;

    /** @test */
    public function testPostActivateAction()
    {
        $this->app->make('orchestra.extension.finder')->addPath(__DIR__.'/../../extensions/');

        $this->actingAs($this->adminUser)
            ->makeRequest('POST', 'admin/extensions/acme/story/activate')
            ->seePageIs('admin/extensions');
    }
}
