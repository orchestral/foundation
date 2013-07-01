<?php namespace Orchestra\Foundation\Tests\Services\Validation;

use Mockery as m;
use Orchestra\Foundation\Services\Validation\Auth;
use Illuminate\Support\Facades\Validator;

class AuthTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Services\Validation\Auth.
	 *
	 * @test
	 */
	public function testValidation()
	{
		$input = array('email' => 'admin@orchestraplatform.com', 'password' => '123');
		$rules = array('email' => array('required', 'email'));
		
		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$stub       = new Auth;
		$validation = $stub->with($input);

		$this->assertTrue($validation);
	}

	/**
	 * Test Orchestra\Foundation\Services\Validation\Auth on login.
	 *
	 * @test
	 */
	public function testValidationOnLogin()
	{
		$input = array('email' => 'admin@orchestraplatform.com', 'password' => '123');
		$rules = array('email' => array('required', 'email'), 'password' => array('required'));
		
		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$stub       = new Auth;
		$validation = $stub->on('login')->with($input);

		$this->assertTrue($validation);
	}
}
