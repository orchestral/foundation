<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Foundation\Services\TestCase;

class RegisterControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/register
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		$user = m::mock('\Orchestra\Model\User');

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::register')->andReturn('register');
		\Orchestra\Support\Facades\Form::swap($form = m::mock('FormGrid'));

		$form->shouldReceive('of')->once()->with('orchestra.account', m::type('Closure'))->andReturn($form)
			->shouldReceive('extend')->once()->with(m::type('Closure'))->andReturnUsing(
				function ($c) use ($form)
				{
					$c($form);
				});

		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::credential.register', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/register');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/register
	 *
	 * @test
	 */
	public function testPostIndexAction()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');
		$memory     = m::mock('Memory\Manager');
		$mailer     = m::mock('Mailer');

		$validation->shouldReceive('on')->once()->with('register')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);
		$user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with(m::any())->andReturn(null)
			->shouldReceive('toArray')->once()->andReturn($input);
		$memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('foo');
		$mailer->shouldReceive('subject')->once()->andReturn(null)
			->shouldReceive('to')->once()->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});
		\Orchestra\Support\Facades\Mail::shouldReceive('push')->once()
			->with('orchestra/foundation::email.credential.register', m::type('Array'), m::type('Closure'))
			->andReturnUsing(function ($v, $d, $c) use ($mailer)
				{
					$c($mailer);
					return array('email@orchestraplatform.com');
				});
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->twice()
			->with('success', m::any())->andReturn(null);

		$this->call('POST', 'admin/register', $input);
		$this->assertRedirectedTo('login');
	}

	/**
	 * Test POST /admin/register failed to send email.
	 *
	 * @test
	 */
	public function testPostIndexActionGivenFailedToSendEmail()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');
		$memory     = m::mock('Memory\Manager');
		$mailer     = m::mock('Mailer');

		$validation->shouldReceive('on')->once()->with('register')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);
		$user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with(m::any())->andReturn(null)
			->shouldReceive('toArray')->once()->andReturn($input);
		$memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('foo')
			->shouldReceive('get')->once()->with('email.queue', false)->andReturn(false);
		$mailer->shouldReceive('subject')->once()->andReturn(null)
			->shouldReceive('to')->once()->andReturn(null);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('memory')->once()->andReturn($memory);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::login')->andReturn('login');
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});
		\Orchestra\Support\Facades\Mail::shouldReceive('push')->once()
			->with('orchestra/foundation::email.credential.register', m::type('Array'), m::type('Closure'))
			->andReturnUsing(function ($v, $d, $c) use ($mailer)
				{
					$c($mailer);
					return array();
				});
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		$this->call('POST', 'admin/register', $input);
		$this->assertRedirectedTo('login');
	}

	/**
	 * Test POST /admin/register with database error.
	 *
	 * @test
	 */
	public function testPostIndexActionGivenDatabaseError()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$user       = m::mock('\Orchestra\Model\User');
		$validation = m::mock('UserAccount');

		$validation->shouldReceive('on')->once()->with('register')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(false);
		$user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null);
		
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::register')->andReturn('register');
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andThrow('\Exception');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);

		$this->call('POST', 'admin/register', $input);
		$this->assertRedirectedTo('register');
	}

	/**
	 * Test POST /admin/register with failed validation.
	 *
	 * @test
	 */
	public function testPostIndexActionGivenFailedValidation()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
		);

		$validation = m::mock('UserAccount');

		$validation->shouldReceive('on')->once()->with('register')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Foundation\Services\Validation\UserAccount')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra::register')->andReturn('register');

		$this->call('POST', 'admin/register', $input);
		$this->assertRedirectedTo('register');
	}
}
