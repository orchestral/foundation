<?php

namespace Orchestra\Tests\Feature\Processor;

use Mockery as m;
use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processor\DeauthenticateUser;

class DeauthenticateUserTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_deauthenticate_user()
    {
        $this->be($user = User::faker()->create());

        $this->assertAuthenticated();

        $stub = $this->app->make(DeauthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\DeauthenticateUser');
        $listener->shouldReceive('userHasLoggedOut')->once()->andReturn('logged.out');

        $this->assertEquals('logged.out', $stub->logout($listener));

        $this->assertGuest();
    }
}
