<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

class UsersControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/users
	 *
	 * @test
	 */
	public function testGetIndexAction()
	{
		$user  = m::mock('\Orchestra\Model\User');
		$role  = m::mock('\Orchestra\Model\Role');
		$table = m::mock('\Orchestra\Html\Table\TableBuilder');

		$user->shouldReceive('search')->once()->with('', array())->andReturn($user)
			->shouldReceive('paginate')->once()->with(30)->andReturn(array());
		$role->shouldReceive('lists')->once()->with('name', 'id')->andReturn(array());
		$table->shouldReceive('extend')->once()->with(m::type('Closure'))->andReturn($table);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.role')->andReturn($role);
		\Orchestra\Support\Facades\Table::shouldReceive('of')->once()
			->with('orchestra.users', m::type('Closure'))->andReturn($table);
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::users.index', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/users');
		$this->assertResponseOk();
	}

	/**
	 * Test GET /admin/users/create
	 *
	 * @test
	 */
	public function testGetCreateAction()
	{
		$form = m::mock('\Orchestra\Html\Form\FormBuilder');

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn(array());
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.users', m::type('Closure'))->andReturn($form);
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::users.edit', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/users/create');
		$this->assertResponseOk();
	}

	/**
	 * Test GET /admin/users/(:any)/edit
	 *
	 * @test
	 */
	public function testGetEditAction()
	{
		$builder = m::mock('UserModelBuilder');
		$user    = m::mock('\Orchestra\Model\User');
		$form    = m::mock('FormBuilder');

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		\Orchestra\Support\Facades\Form::shouldReceive('of')->once()
			->with('orchestra.users', m::type('Closure'))->andReturn($form);
		\Illuminate\Support\Facades\View::shouldReceive('make')->once()
			->with('orchestra/foundation::users.edit', m::type('Array'))->andReturn('foo');

		$this->call('GET', 'admin/users/foo/edit');
		$this->assertResponseOk();
	}

	/**
	 * Test POST /admin/users
	 *
	 * @test
	 */
	public function testPostStoreAction()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		$user       = m::mock('\Orchestra\Model\User');
		$auth       = (object) array(
			'id' => 'foobar',
		);

		$user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andReturn(null);
		$validation->shouldReceive('on')->once()->with('create')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('POST', 'admin/users', $input);
		$this->assertRedirectedTo('users');
	}

	/**
	 * Test POST /admin/users when database error.
	 *
	 * @test
	 */
	public function testPostStoreActionGivenDatabaseError()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		$user       = m::mock('\Orchestra\Model\User');
		$auth       = (object) array(
			'id' => 'foobar',
		);

		$user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andThrow('\Exception');
		$validation->shouldReceive('on')->once()->with('create')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($user);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('POST', 'admin/users', $input);
		$this->assertRedirectedTo('users');
	}

	/**
	 * Test POST /admin/users when validation error.
	 *
	 * @test
	 */
	public function testPostStoreActionGivenValidationError()
	{
		$input = array(
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		$user       = m::mock('\Orchestra\Model\User');
		$auth       = (object) array(
			'id' => 'foobar',
		);

		$validation->shouldReceive('on')->once()->with('create')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users/create')->andReturn('users/create');

		$this->call('POST', 'admin/users', $input);
		$this->assertRedirectedTo('users/create');
		$this->assertSessionHasErrors();
	}

	/**
	 * Test PUT /admin/users/(:any)
	 *
	 * @test
	 */
	public function testPutUpdateAction()
	{
		$input = array(
			'id'       => 'foo',
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		$builder    = m::mock('UserModelBuilder');
		$user       = m::mock('\Orchestra\Model\User');
		$auth       = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andReturn(null);
		$validation->shouldReceive('on')->once()->with('update')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('PUT', 'admin/users/foo', $input);
		$this->assertRedirectedTo('users');
	}

	/**
	 * Test PUT /admin/users/(:any) when database error.
	 *
	 * @test
	 */
	public function testPutUpdateActionGivenDatabaseError()
	{
		$input = array(
			'id'       => 'foo',
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		$builder    = m::mock('UserModelBuilder');
		$user       = m::mock('\Orchestra\Model\User');
		$auth       = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andThrow('\Exception');
		$validation->shouldReceive('on')->once()->with('update')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('PUT', 'admin/users/foo', $input);
		$this->assertRedirectedTo('users');
	}

	/**
	 * Test PUT /admin/users/(:any) when validation error.
	 *
	 * @test
	 */
	public function testPutUpdateActionGivenValidationError()
	{
		$input = array(
			'id'       => 'foo',
			'email'    => 'email@orchestraplatform.com',
			'fullname' => 'Administrator',
			'password' => '123456',
			'roles'    => array(1),
		);

		$validation = m::mock('UserValidation');
		
		$validation->shouldReceive('on')->once()->with('update')->andReturn($validation)
			->shouldReceive('with')->once()->with($input)->andReturn($validation)
			->shouldReceive('fails')->once()->andReturn(true);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('Orchestra\Services\Validation\User')->andReturn($validation);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users/foo/edit')->andReturn('users/foo/edit');

		$this->call('PUT', 'admin/users/foo', $input);
		$this->assertRedirectedTo('users/foo/edit');
		$this->assertSessionHasErrors();
	}

	/**
	 * Test GET /admin/users/(:any)/delete
	 *
	 * @test
	 */
	public function testGetDeleteAction()
	{
		$builder = m::mock('UserModelBuilder');
		$user    = m::mock('\Orchestra\Model\User');
		$auth    = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn('foo')
			->shouldReceive('delete')->once()->andReturn(null);

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()->andReturn($auth);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('GET', 'admin/users/foo/delete');
		$this->assertRedirectedTo('users');
	}

	/**
	 * Test GET /admin/users/(:any)/delete when database error.
	 *
	 * @test
	 */
	public function testGetDeleteActionGivenDatabaseError()
	{
		$builder = m::mock('UserModelBuilder');
		$user    = m::mock('\Orchestra\Model\User');
		$auth    = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('getAttribute')->once()->with('id')->andReturn('foo')
			->shouldReceive('delete')->once()->andThrow('\Exception');

		\Orchestra\Support\Facades\App::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		\Orchestra\Support\Facades\App::shouldReceive('handles')->once()
			->with('orchestra/foundation::users')->andReturn('users');
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);
		\Illuminate\Support\Facades\Auth::shouldReceive('user')->once()->andReturn($auth);
		\Illuminate\Support\Facades\DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('GET', 'admin/users/foo/delete');
		$this->assertRedirectedTo('users');
	}
}
