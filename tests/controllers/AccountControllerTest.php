<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

class AccountControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/account
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn('auth');
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.account', m::type('Closure'))->andReturn('form');

		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::account.index', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/account');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/account
	 *
	 * @test
	 */
	public function testPostIndexAction()
	{
		$input = array(
			'id'       => 1,
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account')->andReturn('account');
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);

		$this->call('POST', 'admin/account', $input);
		$this->assertRedirectedTo('account');
	}

	/**
	 * Test POST /admin/account with database error.
	 *
	 * @test
	 */
	public function testPostIndexActionGivenDatabaseError()
	{
		$input = array(
			'id'       => 1,
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->never()->andReturn(null);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account')->andReturn('account');
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andThrow('\Exception');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		$this->call('POST', 'admin/account', $input);
		$this->assertRedirectedTo('account');
	}

	/**
	 * Test POST /admin/account with validation fails.
	 *
	 * @test
	 */
	public function testPostIndexActionGivenValidationFails()
	{
		$input = array(
			'id'       => 1,
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id']);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account')->andReturn('account');

		$this->call('POST', 'admin/account', $input);
		$this->assertRedirectedTo('account');
		$this->assertSessionHasErrors();
	}

	/**
	 * Test GET /admin/account/password
	 *
	 * @test
	 */
	public function testGetPasswordAction()
	{
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn('auth');
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.account: password', m::type('Closure'))->andReturn('form');

		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::account.password', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/account/password');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/account/password
	 *
	 * @test
	 */
	public function testPostPasswordAction()
	{
		$input = array(
			'id'               => 1,
			'current_password' => '123456',
			'new_password'     => 'qwerty',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('on')->once()->with('changePassword')->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
			->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring')
			->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Illuminate\Support\Facades\Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(true);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c) 
				{
					$c();
				});
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account/password')->andReturn('account/password');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);

		$this->call('POST', 'admin/account/password', $input);
		$this->assertRedirectedTo('account/password');
	}

	/**
	 * Test POST /admin/account/password with database error.
	 *
	 * @test
	 */
	public function testPostPasswordActionGivenDatabaseError()
	{
		$input = array(
			'id'               => 1,
			'current_password' => '123456',
			'new_password'     => 'qwerty',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('on')->once()->with('changePassword')->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
			->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring')
			->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturn(null)
			->shouldReceive('save')->never()->andReturn(null);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Illuminate\Support\Facades\Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(true);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andThrow('\Exception');
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account/password')->andReturn('account/password');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		$this->call('POST', 'admin/account/password', $input);
		$this->assertRedirectedTo('account/password');
	}

	/**
	 * Test POST /admin/account/password with hash error.
	 *
	 * @test
	 */
	public function testPostPasswordActionGivenHashError()
	{
		$input = array(
			'id'               => 1,
			'current_password' => '123456',
			'new_password'     => 'qwerty',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('on')->once()->with('changePassword')->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
			->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring');

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Illuminate\Support\Facades\Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(false);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account/password')->andReturn('account/password');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		$this->call('POST', 'admin/account/password', $input);
		$this->assertRedirectedTo('account/password');
	}

	/**
	 * Test POST /admin/account/password with validation fails.
	 *
	 * @test
	 */
	public function testPostPasswordActionGivenValidationFails()
	{
		$input = array(
			'id'               => 1,
			'current_password' => '123456',
			'new_password'     => 'qwerty',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('on')->once()->with('changePassword')->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id']);

		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()
			->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::account/password')->andReturn('account/password');

		$this->call('POST', 'admin/account/password', $input);
		$this->assertRedirectedTo('account/password');
		$this->assertSessionHasErrors();
	}
}
