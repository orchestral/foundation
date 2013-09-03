<?php namespace Orchestra\Foundation\Tests\Services\Validation;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Services\Validation\Setting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class SettingTest extends \PHPUnit_Framework_TestCase {
	
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
	 * Test Orchestra\Foundation\Services\Validation\Setting.
	 *
	 * @test
	 */
	public function testValidation()
	{
		$input = array(
			'site_name'     => 'Orchestra Platform',
			'email_address' => 'admin@orchestraplatform.com',
			'email_driver'  => 'mail',
			'email_port'    => 25,
		);

		$rules = array(
			'site_name'     => array('required'),
			'email_address' => array('required', 'email'),
			'email_driver'  => array('required', 'in:mail,smtp,sendmail'),
			'email_port'    => array('numeric'),
		);
		
		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event\Dispatcher');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new Setting;
		$validation = $stub->with($input);

		$this->assertTrue($validation);
	}

	/**
	 * Test Orchestra\Foundation\Services\Validation\Setting on stmp 
	 * setting.
	 *
	 * @test
	 */
	public function testValidationOnSmtp()
	{
		$input = array(
			'site_name'      => 'Orchestra Platform',
			'email_address'  => 'admin@orchestraplatform.com',
			'email_driver'   => 'mail',
			'email_port'     => 25,
			'email_username' => 'admin@orchestraplatform.com',
			'email_password' => '123456',
		);

		$rules = array(
			'site_name'      => array('required'),
			'email_address'  => array('required', 'email'),
			'email_driver'   => array('required', 'in:mail,smtp,sendmail'),
			'email_port'     => array('numeric'),
			'email_username' => array('required'),
			'email_host'     => array('required'),
		);

		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event\Dispatcher');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new Setting;
		$validation = $stub->on('smtp')->with($input);

		$this->assertTrue($validation);
	}

	/**
	 * Test Orchestra\Foundation\Services\Validation\Setting on sendmail 
	 * setting.
	 *
	 * @test
	 */
	public function testValidationOnSendmail()
	{
		$input = array(
			'site_name'      => 'Orchestra Platform',
			'email_address'  => 'admin@orchestraplatform.com',
			'email_driver'   => 'sendmail',
			'email_port'     => 25,
			'email_sendmail' => '/usr/bin/sendmail -t',
		);

		$rules = array(
			'site_name'      => array('required'),
			'email_address'  => array('required', 'email'),
			'email_driver'   => array('required', 'in:mail,smtp,sendmail'),
			'email_port'     => array('numeric'),
			'email_sendmail' => array('required'),
		);

		$validator = m::mock('Validator\Environment');
		$validator->shouldReceive('make')->once()->with($input, $rules)->andReturn(true);
		Validator::swap($validator);

		$events = m::mock('Event\Dispatcher');
		$events->shouldReceive('fire')->once()->with('orchestra.validate: settings', m::any())->andReturn(null);
		Event::swap($events);

		$stub       = new Setting;
		$validation = $stub->on('sendmail')->with($input);

		$this->assertTrue($validation);
	}
}
