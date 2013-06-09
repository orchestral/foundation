<?php namespace Orchestra\Foundation\Tests\Reminders;

use Mockery as m;
use Orchestra\Foundation\Reminders\PasswordBroker;

class PasswordBrokerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::remind() method.
	 *
	 * @test
	 */
	public function testRemindMethod()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$callback = function () {};

		$user->shouldReceive('retrieveByCredentials')->once()
			->with(array('username' => 'user-foo'))
			->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
		$reminders->shouldReceive('create')->once()->with($userReminderable)->andReturn(null);
		$userReminderable->shouldReceive('getReminderEmail')->once()->andReturn('foo@reminderable.com');
		$mailer->shouldReceive('to')->once()->with('foo@reminderable.com')->andReturn(null)
			->shouldReceive('send')->once()
				->with('foo', m::any(), m::type('Closure'))->andReturnUsing(
					function ($v, $d, $c) use ($mailer)
					{
						$c($mailer);
					}
				);
		$messages->shouldReceive('add')->once()->with('success', m::any())->andReturn(null);
		$redirector->shouldReceive('refresh')->once()->andReturn('foo');

		$this->assertEquals('foo', $stub->remind(array('username' => 'user-foo')));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::remind() given 
	 * user is null.
	 *
	 * @test
	 */
	public function testRemindMethodGivenUserIsNull()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$user->shouldReceive('retrieveByCredentials')->once()
			->with(array('username' => 'user-foo'))->andReturn(null);
		$messages->shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
		$redirector->shouldReceive('refresh')->once()->andReturn('foo');
		
		$this->assertEquals('foo', $stub->remind(array('username' => 'user-foo')));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method.
	 *
	 * @test
	 */
	public function testResetMethod()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$callback = function($user, $pass) {
			return 'foo';
		};

		$user->shouldReceive('retrieveByCredentials')->once()->with(array('username' => 'user-foo'))
			->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
		$reminders->shouldReceive('exists')->once()->with($userReminderable, 'someuniquetokenkey')->andReturn(true)
			->shouldReceive('delete')->once()->with('someuniquetokenkey')->andReturn(true);
		$redirector->shouldReceive('getUrlGenerator')->andReturn($redirector)
			->shouldReceive('getRequest')->times(5)->andReturn($request = m::mock('RequestBag'));
		$request->shouldReceive('input')->twice()->with('password')->andReturn('qwerty')
			->shouldReceive('input')->once()->with('password_confirmation')->andReturn('qwerty')
			->shouldReceive('input')->twice()->with('token')->andReturn('someuniquetokenkey');
		
		$this->assertEquals('foo', $stub->reset(array('username' => 'user-foo'), $callback));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method 
	 * given user ins not \Illuminate\Auth\Reminders\RemindableInteface.
	 *
	 * @test
	 */
	public function testResetMethodGivenUserIsNotRemindableInterface()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$callback = function($user, $pass) {};

		$user->shouldReceive('retrieveByCredentials')->once()->with(array('username' => 'user-foo'))->andReturn('foo');
		$messages->shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
		$redirector->shouldReceive('refresh')->once()->andReturn('foo');
		
		$this->assertEquals('foo', $stub->reset(array('username' => 'user-foo'), $callback));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method 
	 * given fail verify password.
	 *
	 * @test
	 */
	public function testResetMethodGivenFailVerifyPassword()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$callback = function($user, $pass) {};

		$user->shouldReceive('retrieveByCredentials')->once()->with(array('username' => 'user-foo'))
			->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
		$messages->shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
		$redirector->shouldReceive('getUrlGenerator')->andReturn($redirector)
			->shouldReceive('getRequest')->twice()->andReturn($request = m::mock('RequestBag'))
			->shouldReceive('refresh')->once()->andReturn('foo');
		$request->shouldReceive('input')->once()->with('password')->andReturn('qwerty')
			->shouldReceive('input')->once()->with('password_confirmation')->andReturn('notqwerty');
		
		$this->assertEquals('foo', $stub->reset(array('username' => 'user-foo'), $callback));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method 
	 * given reminder not exist.
	 *
	 * @test
	 */
	public function testResetMethodGivenReminderNotExist()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$callback = function($user, $pass) {};

		$user->shouldReceive('retrieveByCredentials')->once()->with(array('username' => 'user-foo'))
			->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
		$reminders->shouldReceive('exists')->once()->with($userReminderable, 'someuniquetokenkey')->andReturn(false);
		$messages->shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
		$redirector->shouldReceive('getUrlGenerator')->andReturn($redirector)
			->shouldReceive('getRequest')->times(3)->andReturn($request = m::mock('RequestBag'))
			->shouldReceive('refresh')->once()->andReturn('foo');
		$request->shouldReceive('input')->once()->with('password')->andReturn('qwerty')
			->shouldReceive('input')->once()->with('password_confirmation')->andReturn('qwerty')
			->shouldReceive('input')->once()->with('token')->andReturn('someuniquetokenkey');
		
		$this->assertEquals('foo', $stub->reset(array('username' => 'user-foo'), $callback));
	}

	/**
	 * Test Orchestra\Foundation\Reminders\PasswordBroker::getUser() method  
	 * throws exception.
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testGetUserThrowsException()
	{
		$stub = new PasswordBroker(
			$reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
			$user = m::mock('\Illuminate\Auth\UserProviderInterface'),
			$redirector = m::mock('\Illuminate\Routing\Redirector'),
			$mailer = m::mock('\Orchestra\Foundation\Mail'),
			$messages = m::mock('\Orchestra\Support\Messages'),
			$view = 'foo'
		);

		$user->shouldReceive('retrieveByCredentials')->once()->with(array())->andReturn('foo');

		$stub->getUser(array());
	}
}
