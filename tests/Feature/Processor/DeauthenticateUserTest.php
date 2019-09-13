<?php

namespace Orchestra\Tests\Feature\Processor;

use Mockery as m;
use Orchestra\Foundation\Processors\DeauthenticateUser;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class DeauthenticateUserTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_deauthenticate_user()
    {
        $this->be($user = $this->createUserAsMember());

        $this->assertAuthenticated();

        $stub = $this->app->make(DeauthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\DeauthenticateUser');
        $listener->shouldReceive('userHasLoggedOut')->once()->andReturn('logged.out');

        $this->assertEquals('logged.out', $stub($listener));

        $this->assertGuest();
    }
}
