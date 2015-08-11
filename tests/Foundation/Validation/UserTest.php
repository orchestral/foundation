<?php namespace Orchestra\Foundation\Tests\Validation;

use Mockery as m;
use Orchestra\Foundation\Validation\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Validation\User.
     *
     * @test
     */
    public function testInstance()
    {
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');

        $stub = new User($factory, $events);

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validation\User validation.
     *
     * @test
     */
    public function testValidation()
    {
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles' => 1,
        ];

        $rules = [
            'email' => ['required', 'email'],
            'fullname' => ['required'],
            'roles' => ['required'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);

        $events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();

        $stub = new User($factory, $events);
        $validation = $stub->with($input);

        $this->assertEquals($validator, $validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\User on create.
     *
     * @test
     */
    public function testValidationOnCreate()
    {
        $events = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $factory = m::mock('\Illuminate\Contracts\Validation\Factory');
        $validator = m::mock('\Illuminate\Contracts\Validation\Validator');

        $input = [
            'email' => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles' => 1,
            'password' => '123456',
        ];

        $rules = [
            'email' => ['required', 'email'],
            'fullname' => ['required'],
            'roles' => ['required'],
            'password' => ['required'],
        ];

        $factory->shouldReceive('make')->once()->with($input, $rules, [])->andReturn($validator);
        $events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();

        $stub = new User($factory, $events);
        $validation = $stub->on('create')->with($input);

        $this->assertEquals($validator, $validation);
    }
}
