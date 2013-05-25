<?php namespace Orchestra\Foundation\Tests\Services\Validation;

use Mockery as m;
use Orchestra\Services\Validation\UserAccount;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class UserAccountTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Services\Validation\UserAccount.
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
		
		$validator = m::mock('Validator');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new UserAccount;
		$validation = $stub->with($input);

		$this->assertTrue($validation);
	}

	/**
	 * Test Orchestra\Services\Validation\User on create setting.
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

		$validator = m::mock('Validator');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new UserAccount;
		$validation = $stub->on('register')->with($input);

		$this->assertTrue($validation);
	}

	/**
	 * Test Orchestra\Services\Validation\UserAccount on change password.
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

		$validator = m::mock('Validator');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$stub       = new UserAccount;
		$validation = $stub->on('changePassword')->with($input);

		$this->assertTrue($validation);
	}
}
