<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;

class CredentialControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/login
	 *
	 * @test
	 */
	public function testGetLoginAction()
	{
		$this->call('GET', 'admin/login');
		$this->assertResponseOk();

		$this->assertTrue(Site::has('title'));
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

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		Auth::shouldReceive('attempt')->once()->with(m::type('Array'), true)->andReturn(true);
		Auth::shouldReceive('user')->once()->andReturn($user);
		Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

		Orchestra::shouldReceive('handles')->once()
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

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		Auth::shouldReceive('attempt')->once()
			->with(m::type('Array'), true)->andReturn(false);
		Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

		Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

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

		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\Auth')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

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
		Auth::shouldReceive('logout')->once()->andReturn(null);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

		$this->call('GET', 'admin/logout');
		$this->assertRedirectedTo('login');
	}

	/**
	 * Test GET /admin/logout?redirect=home
	 *
	 * @test
	 */
	public function testDeleteLoginActionWithRedirection()
	{
		Auth::shouldReceive('logout')->once()->andReturn(null);
		Orchestra::shouldReceive('handles')->once()->with('home')->andReturn('home');

		$this->call('GET', 'admin/logout', array('redirect' => 'home'));
		$this->assertRedirectedTo('home');
	}
}
