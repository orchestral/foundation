<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

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
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
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

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()
			->andReturn($memory = m::mock('Memory'));
		\Illuminate\Support\Facades\Password::swap($password = m::mock('PasswordBroker'));

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

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
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
		$view = m::mock('View\Environment');

		\Illuminate\Support\Facades\View::swap($view);

		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::forgot.reset')->andReturn(m::self());
		\Illuminate\Support\Facades\View::shouldReceive('with')->once()
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

		\Illuminate\Support\Facades\Password::swap($password = m::mock('PasswordBroker'));
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');
		$user->shouldReceive('setAttribute')->once()
				->with('password', 'foo')->andReturn(null)
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
