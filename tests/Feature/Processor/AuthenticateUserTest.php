<?php

namespace Orchestra\Tests\Feature\Processor;

use Mockery as m;
use Orchestra\Foundation\Processors\AuthenticateUser;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Tests\Feature\TestCase;

class AuthenticateUserTest extends TestCase
{
    use Installation;

    /** @test */
    public function it_can_authenticate_a_user()
    {
        $user = $this->createUserAsMember();

        $data = [
            'username' => $user->email,
            'password' => 'secret',
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $this->assertGuest();

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userHasLoggedIn')->once()->andReturn('logged.in');

        $this->assertEquals('logged.in', $stub($listener, $data));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_cant_authenticate_user_given_invalid_credential()
    {
        $user = $this->createUserAsMember();

        $data = [
            'username' => 'crynobone@gmail.com',
            'password' => 'demo123',
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userLoginHasFailedAuthentication')->once()
                ->with(m::type('Array'))->andReturn('login.authentication.failed');

        $this->assertEquals('login.authentication.failed', $stub($listener, $data));

        $this->assertGuest();
    }

    /** @test */
    public function it_cant_authenticate_user_given_validation_fails()
    {
        $user = $this->createUserAsMember();

        $data = [
            'username' => $user->email,
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userLoginHasFailedValidation')->once()
                ->with(m::type('Illuminate\Support\MessageBag'))->andReturn('login.validation.failed');

        $this->assertEquals('login.validation.failed', $stub($listener, $data));

        $this->assertGuest();
    }
}
