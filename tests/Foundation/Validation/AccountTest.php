<?php namespace Orchestra\Foundation\Tests\Validation;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Validation\Account;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class AccountTest extends \PHPUnit_Framework_TestCase
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
     * Test Orchestra\Foundation\Validation\Account.
     *
     * @test
     */
    public function testValidation()
    {
        $input = array(
            'email'    => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
        );

        $rules = array(
            'email'    => array('required', 'email'),
            'fullname' => array('required'),
        );

        $validator = m::mock('\Illuminate\Validation\Factory')->makePartial();
        $validator->shouldReceive('make')->once()->with($input, $rules, array())->andReturn(true);
        Validator::swap($validator);

        $events = m::mock('\Illuminate\Events\Dispatcher')->makePartial();
        $events->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();
        Event::swap($events);

        $stub       = new Account;
        $validation = $stub->with($input);

        $this->assertTrue($validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\User on create setting.
     *
     * @test
     */
    public function testValidationOnRegister()
    {
        $input = array(
            'email'    => 'admin@orchestraplatform.com',
            'fullname' => 'Administrator',
        );

        $rules = array(
            'email'    => array('required', 'email', 'unique:users,email'),
            'fullname' => array('required'),
        );

        $validator = m::mock('\Illuminate\Validation\Factory')->makePartial();
        $validator->shouldReceive('make')->once()->with($input, $rules, array())->andReturn(true);
        Validator::swap($validator);

        $events = m::mock('\Illuminate\Events\Dispatcher')->makePartial();
        $events->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturnNull();
        Event::swap($events);

        $stub       = new Account;
        $validation = $stub->on('register')->with($input);

        $this->assertTrue($validation);
    }

    /**
     * Test Orchestra\Foundation\Validation\Account on change
     * password.
     *
     * @test
     */
    public function testValidationOnChangePassword()
    {
        $input = array(
            'current_password' => '123456',
            'new_password'     => 'qwerty',
            'confirm_password' => 'qwerty',
        );

        $rules = array(
            'current_password' => array('required'),
            'new_password'     => array('required', 'different:current_password'),
            'confirm_password' => array('same:new_password'),
        );

        $validator = m::mock('\Illuminate\Validation\Factory')->makePartial();
        $validator->shouldReceive('make')->once()->with($input, $rules, array())->andReturn(true);
        Validator::swap($validator);

        $stub       = new Account;
        $validation = $stub->on('changePassword')->with($input);

        $this->assertTrue($validation);
    }
}
