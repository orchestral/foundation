<?php namespace Orchestra\Foundation\Validation\TestCase;

use Mockery as m;
use Orchestra\Foundation\Validation\AuthenticateUser;

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
     * Test Orchestra\Foundation\Validation\Auth.
     *
     * @test
     */
    public function testInstance()
    {
        $events  = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');

        $stub = new AuthenticateUser($factory, $events);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validation\Auth validation.
     *
     * @test
     */
    public function testValidation()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = ['email' => 'admin@orchestraplatform.com', 'password' => '123'];
        $rules = ['email' => ['required', 'email']];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);

        $stub       = new AuthenticateUser($factory, $events);
        $validation = $stub->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Auth on login.
     *
     * @test
     */
    public function testValidationOnLogin()
    {
        $events    = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory   = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = ['email' => 'admin@orchestraplatform.com', 'password' => '123'];
        $rules = ['email' => ['required', 'email'], 'password' => ['required']];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);

        $stub       = new AuthenticateUser($factory, $events);
        $validation = $stub->on('login')->with($input);

        $this->assertEquals($validator, $validation);
    }
}
