<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Messages;

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
		$presenter = m::mock('AccountPresenter');
		$presenter->shouldReceive('profileForm')->once()->andReturn('edit.account.form');

		Auth::shouldReceive('user')->once()->andReturn('auth');
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Html\AccountPresenter')->andReturn($presenter);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::account')->andReturn('account');
		View::shouldReceive('make')->once()
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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::account')->andReturn('account');
		DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});
		Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::account')->andReturn('account');
		DB::shouldReceive('transaction')->once()->with(m::type('Closure'))->andThrow('\Exception');
		Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::account')->andReturn('account');

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
		$presenter = m::mock('AccountPresenter');
		$presenter->shouldReceive('passwordForm')->once()->andReturn('edit.password.form');

		Auth::shouldReceive('user')->once()->andReturn('auth');
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Html\AccountPresenter')->andReturn($presenter);
		View::shouldReceive('make')->once()
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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(true);
		DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c) 
				{
					$c();
				});
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::account/password')->andReturn('account/password');
		Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(true);
		DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andThrow('\Exception');
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::account/password')->andReturn('account/password');
		Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Hash::shouldReceive('check')->once()
			->with($input['current_password'], 'hashedstring')->andReturn(false);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::account/password')->andReturn('account/password');
		Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

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

		Auth::shouldReceive('user')->once()->andReturn($user);
		Orchestra::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::account/password')->andReturn('account/password');

		$this->call('POST', 'admin/account/password', $input);
		$this->assertRedirectedTo('account/password');
		$this->assertSessionHasErrors();
	}
}
