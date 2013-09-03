<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;


class ForgotControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/forgot
	 * 
	 * @test
	 */
	public function testGetIndexAction()
	{
		View::shouldReceive('make')->once()
			->with('orchestra/foundation::forgot.index')->andReturn('foo');

		$this->call('GET', 'admin/forgot');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/forgot
	 * 
	 * @test
	 */
	public function testPostIndexAction()
	{
		$input = array(
			'email' => 'email@orchestraplatform.com',
		);

		$validation = m::mock('AuthValidation');
		$mailer     = m::mock('Mailer');

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		Orchestra::shouldReceive('memory')->once()->andReturn($memory = m::mock('Memory'));
		Password::swap($password = m::mock('PasswordBroker'));

		$validation->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);
		$mailer->shouldReceive('subject')->once()->andReturn(null);
		$password->shouldReceive('remind')->once()->andReturnUsing(
			function ($d, $c) use ($mailer)
			{
				$c($mailer);
			});
		$memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('Orchestra Platform');

		$this->call('POST', 'admin/forgot', $input);
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/forgot when validation fails
	 * 
	 * @test
	 */
	public function testPostIndexActionWhenValidationFail()
	{
		$input = array(
			'email' => 'email@orchestraplatform.com',
		);

		$validation = m::mock('AuthValidation');

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::forgot')->andReturn('forgot');

		$validation->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		$this->call('POST', 'admin/forgot', $input);
		$this->assertRedirectedTo('forgot');
		$this->assertSessionHas('errors');
	}

	/**
	 * Test GET /admin/forgot/reset
	 * 
	 * @test
	 */
	public function testGetResetAction()
	{
		View::shouldReceive('make')->once()
			->with('orchestra/foundation::forgot.reset')->andReturn(m::self());
		View::shouldReceive('with')->once()
			->with('token', 'auniquetoken')->andReturn('foo');

		$this->call('GET', 'admin/forgot/reset/auniquetoken');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/forgot/reset
	 * 
	 * @test
	 */
	public function testPostResetAction()
	{
		$input = array(
			'email' => 'email@orchestraplatform.com',
		);

		$user = m::mock('\Orchestra\Model\User');

		Password::swap($password = m::mock('PasswordBroker'));
		Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');
		$user->shouldReceive('setAttribute')->once()->with('password', 'foo')->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null);
		$password->shouldReceive('reset')->once()->with($input, m::type('Closure'))->andReturnUsing(
			function ($d, $c) use ($user)
			{
				return $c($user, 'foo');
			});

		$this->call('POST', 'admin/forgot/reset/auniquetoken', $input);
		$this->assertRedirectedTo('login');
	}
}
