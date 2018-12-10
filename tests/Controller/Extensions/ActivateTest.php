<?php

namespace Orchestra\Tests\Controller\Extensions;

use Orchestra\Support\Facades\Extension;
use Orchestra\Tests\Controller\TestCase;
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
}
