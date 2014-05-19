<?php namespace Orchestra\Foundation\Tests\Validation;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Validation\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(new Container);
    }

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
        $stub = new User;

        $this->assertInstanceOf('\Orchestra\Support\Validator', $stub);
    }

    /**
     * Test Orchestra\Foundation\Validation\User validation.
     *
     * @test
     */
    public function testValidation()
    {
        $input = array(
            'email'    => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles'    => 1,
        );

        $rules = array(
            'email'    => array('required', 'email'),
            'fullname' => array('required'),
            'roles'    => array('required'),
        );

        $factory = m::mock('\Illuminate\Validation\Factory')->makePartial();
        $validator = m::mock('\Illuminate\Validation\Validator');
        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
        Validator::swap($factory);

        $events = m::mock('\Illuminate\Events\Dispatcher')->makePartial();
        $events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();
        Event::swap($events);

        $stub       = new User;
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
        $input = array(
            'email'    => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
            'roles'    => 1,
            'password' => '123456',
        );

        $rules = array(
            'email'    => array('required', 'email'),
            'fullname' => array('required'),
            'roles'    => array('required'),
            'password' => array('required'),
        );

        $factory = m::mock('\Illuminate\Validation\Factory')->makePartial();
        $validator = m::mock('\Illuminate\Validation\Validator');
        $factory->shouldReceive('make')->once()->with($input, $rules, array())->andReturn($validator);
        Validator::swap($factory);

        $events = m::mock('\Illuminate\Events\Dispatcher')->makePartial();
        $events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();
        Event::swap($events);

        $stub       = new User;
        $validation = $stub->on('create')->with($input);

        $this->assertEquals($validator, $validation);
    }
}
