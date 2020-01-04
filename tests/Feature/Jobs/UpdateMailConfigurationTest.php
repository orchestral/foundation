<?php

namespace Orchestra\Tests\Feature\Jobs;

use Orchestra\Foundation\Jobs\UpdateMailConfiguration;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class UpdateMailConfigurationTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_update_mail_configuration()
    {
        $memory = $this->app->make('orchestra.platform.memory');

        $this->assertSame('Orchestra Platform', $memory->get('email.from.name'));
        $this->assertSame($this->adminUser->email, $memory->get('email.from.address'));

        \dispatch_now(new UpdateMailConfiguration('My Platform', 'crynobone@gmail.com'));

        $this->assertSame('My Platform', $memory->get('email.from.name'));
        $this->assertSame('crynobone@gmail.com', $memory->get('email.from.address'));
    }
}
