<?php

namespace Orchestra\Tests\Controller;

use Mockery as m;
use Orchestra\Foundation\Testing\Installation;

class PublishPackagesTest extends TestCase
{
    use Installation;

    /** @test */
    public function its_not_accessible_for_user()
    {
        $user = $this->createUserAsMember();

        $this->actingAs($user)
            ->visit('admin/publisher')
            ->seePageIs('admin');
    }

    /** @test */
    public function it_can_select_publishing_driver()
    {
        $this->instance('orchestra.publisher.filesystem', $client = m::mock('\Orchestra\Contracts\Publisher\Uploader'));

        $client->shouldReceive('connected')->once()->andReturn(true);

        $this->actingAs($this->adminUser)
            ->visit('admin/publisher')
            ->seePageIs('admin/extensions');
    }
}
