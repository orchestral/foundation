<?php

namespace Orchestra\Tests\Feature\Processor;

use Mockery as m;
use Orchestra\Foundation\Auth\User;
use Orchestra\Tests\Feature\TestCase;
use Orchestra\Foundation\Testing\Installation;
use Orchestra\Foundation\Processor\AuthenticateUser;

class AuthenticateUserTest extends TestCase
{
    use Installation;

    /**
     * @test
     */
    public function it_can_authenticate_a_user()
    {
        $user = User::faker()->create();

        $data = [
            'username' => $user->email,
            'password' => 'secret',
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $this->assertGuest();

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userHasLoggedIn')->once()->andReturn('logged.in');

        $this->assertEquals('logged.in', $stub->login($listener, $data));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function it_cant_authenticate_user_given_invalid_credential()
    {
        $user = User::faker()->create();

        $data = [
            'username' => 'crynobone@gmail.com',
            'password' => 'demo123',
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userLoginHasFailedAuthentication')->once()
                ->with(m::type('Array'))->andReturn('login.authentication.failed');

        $this->assertEquals('login.authentication.failed', $stub->login($listener, $data));

        $this->assertGuest();
    }

    /**
     * @test
     */
    public function it_cant_authenticate_user_given_validation_fails()
    {
        $user = User::faker()->create();

        $data = [
            'username' => $user->email,
        ];

        $stub = $this->app->make(AuthenticateUser::class);

        $listener = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $listener->shouldReceive('userLoginHasFailedValidation')->once()
                ->with(m::type('Illuminate\Support\MessageBag'))->andReturn('login.validation.failed');

        $this->assertEquals('login.validation.failed', $stub->login($listener, $data));

        $this->assertGuest();
    }
}
