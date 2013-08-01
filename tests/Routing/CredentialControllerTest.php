<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

class CredentialControllerTest extends TestCase {

	/**
	 * Test GET /admin/login
	 *
	 * @test
	 */
	public function testGetLoginAction()
	{
		$this->call('GET', 'admin/login');
		$this->assertResponseOk();

		$this->assertTrue(\Orchestra\Support\Facades\Site::has('title'));
	}

	/**
	 * Test POST /admin/login
	 *
	 * @test
	 */
	public function testPostLoginAction()
	{
		$input = array(
			'email'    => 'hello@orchestraplatform.com',
			'password' => '123456',
			'remember' => 'yes',
		);

		$user       = m::mock('Orchestra\Model\User');
		$validation = m::mock('AuthValidator');

		$validation->shouldReceive('on')->once()->with('login')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('status')->andReturn(0)
			->shouldReceive('setAttribute')->once()->with('status', 1)->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		\Illuminate\Support\Facades\Auth::shouldReceive('attempt')->once()
			->with(m::type('Array'), true)->andReturn(true);
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()->andReturn($user);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::/')->andReturn('/');

		$this->call('POST', 'admin/login', $input);
		$this->assertRedirectedTo('/');
	}

	/**
	 * Test POST /admin/login when authentication failed.
	 *
	 * @test
	 */
	public function testPostLoginActionGivenAuthenticationFails()
	{
		$input = array(
			'email'    => 'hello@orchestraplatform.com',
			'password' => '123456',
			'remember' => 'yes',
		);

		$validation = m::mock('AuthValidator');

		$validation->shouldReceive('on')->once()->with('login')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		\Illuminate\Support\Facades\Auth::shouldReceive('attempt')->once()
			->with(m::type('Array'), true)->andReturn(false);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');

		$this->call('POST', 'admin/login', $input);
		$this->assertRedirectedTo('login');
	}

	/**
	 * Test POST /admin/login when validation failed.
	 *
	 * @test
	 */
	public function testPostLoginActionGivenValidationFails()
	{
		$input = array(
			'email'    => 'hello@orchestraplatform.com',
			'password' => '123456',
			'remember' => 'yes',
		);

		$validation = m::mock('AuthValidator');

		$validation->shouldReceive('on')->once()->with('login')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');

		$this->call('POST', 'admin/login', $input);
		$this->assertRedirectedTo('login');
		$this->assertSessionHasErrors();
	}

	/**
	 * Test GET /admin/logout
	 *
	 * @test
	 */
	public function testDeleteLoginAction()
	{
		\Illuminate\Support\Facades\Auth::shouldReceive('logout')->once()->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');

		$this->call('GET', 'admin/logout');
		$this->assertRedirectedTo('login');
	}
}
