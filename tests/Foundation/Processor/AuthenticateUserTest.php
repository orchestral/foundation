<?php namespace Orchestra\Foundation\Processor\TestCase;

use Mockery as m;
use Orchestra\Foundation\Processor\AuthenticateUser;

class AuthenticateUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\AuthenticateUser::login()
     * method.
     *
     * @test
     */
    public function testLoginMethod()
    {
        $listener  = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $validator = m::mock('\Orchestra\Foundation\Validation\AuthenticateUser');
        $resolver  = m::mock('\Illuminate\Contracts\Validation\Validator');
        $auth      = m::mock('\Orchestra\Contracts\Auth\Guard');
        $user      = m::mock('\Orchestra\Model\User, \Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(false);
        $auth->shouldReceive('attempt')->once()->with(m::type('Array'), true)->andReturn(true)
            ->shouldReceive('getUser')->once()->andReturn($user);
        $user->shouldReceive('getAttribute')->once()->with('status')->andReturn(0)
            ->shouldReceive('activate')->once()->andReturnSelf()
            ->shouldReceive('save')->once()->andReturnNull();
        $listener->shouldReceive('userHasLoggedIn')->once()->andReturn('logged.in');

        $stub = new AuthenticateUser($auth, $validator);

        $this->assertEquals('logged.in', $stub->login($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\AuthenticateUser::login()
     * method given failed authentication.
     *
     * @test
     */
    public function testLoginMethodGivenFailedAuthentication()
    {
        $listener  = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $validator = m::mock('\Orchestra\Foundation\Validation\AuthenticateUser');
        $resolver  = m::mock('\Illuminate\Contracts\Validation\Validator');
        $auth      = m::mock('\Orchestra\Contracts\Auth\Guard');

        $input = $this->getInput();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(false);
        $auth->shouldReceive('attempt')->once()->with(m::type('Array'), true)->andReturn(false);
        $listener->shouldReceive('userLoginHasFailedAuthentication')->once()
                ->with(m::type('Array'))->andReturn('login.authentication.failed');

        $stub = new AuthenticateUser($auth, $validator);

        $this->assertEquals('login.authentication.failed', $stub->login($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\AuthenticateUser::login()
     * method given failed validation.
     *
     * @test
     */
    public function testLoginMethodGivenFailedValidation()
    {
        $listener  = m::mock('\Orchestra\Contracts\Auth\Listener\AuthenticateUser');
        $validator = m::mock('\Orchestra\Foundation\Validation\AuthenticateUser');
        $resolver  = m::mock('\Illuminate\Contracts\Validation\Validator');
        $auth      = m::mock('\Orchestra\Contracts\Auth\Guard');

        $input = $this->getInput();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(true)
            ->shouldReceive('getMessageBag')->once()->andReturn([]);
        $listener->shouldReceive('userLoginHasFailedValidation')->once()
                ->with(m::type('Array'))->andReturn('login.validation.failed');

        $stub = new AuthenticateUser($auth, $validator);

        $this->assertEquals('login.validation.failed', $stub->login($listener, $input));
    }

    /**
     * Get sample input.
     *
     * @return array
     */
    protected function getInput()
    {
        return [
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];
    }
}
