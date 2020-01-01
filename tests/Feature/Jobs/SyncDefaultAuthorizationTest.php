<?php

namespace Orchestra\Tests\Feature\Jobs;

use Orchestra\Foundation\Jobs\SyncDefaultAuthorization;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class SyncDefaultAuthorizationTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_be_synced()
    {
        $acl = \app('orchestra.platform.acl');

        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Users'));
        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Orchestra'));
        $this->assertFalse($acl->actions()->has('Manage Roles'));
        $this->assertFalse($acl->actions()->has('Manage Acl'));

        \dispatch_now(new SyncDefaultAuthorization());

        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Users'));
        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Orchestra'));
        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Roles'));
        $this->assertTrue($acl->canAs($this->adminUser, 'Manage Acl'));
    }
}
