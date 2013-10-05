<?php namespace Orchestra\Foundation\Tests\Validation;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Validation\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class UserTest extends \PHPUnit_Framework_TestCase {
	
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
		
		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event\Dispatcher');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new User;
		$validation = $stub->with($input);

		$this->assertTrue($validation);
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

		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event\Dispatcher');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: users', m::any())->andReturn(null)
			->shouldReceive('fire')->once()->with('orchestra.validate: user.account', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new User;
		$validation = $stub->on('create')->with($input);

		$this->assertTrue($validation);
	}
}
