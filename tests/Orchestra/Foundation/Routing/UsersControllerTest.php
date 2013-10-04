<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Table;

class UsersControllerTest extends TestCase {
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Bind dependencies.
	 *
	 * @return array
	 */
	protected function bindDependencies()
	{
		$presenter = m::mock('\Orchestra\Foundation\Services\Html\UserPresenter');
		$validator = m::mock('\Orchestra\Foundation\Services\Validation\User');

		App::instance('Orchestra\Foundation\Services\Html\UserPresenter', $presenter);
		App::instance('Orchestra\Foundation\Services\Validation\User', $validator);

		return array($presenter, $validator);
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

		list($presenter, ) = $this->bindDependencies();

		$user->shouldReceive('search')->once()->with('', array())->andReturn($user)
			->shouldReceive('paginate')->once()->andReturn(array());
		$role->shouldReceive('lists')->once()->with('name', 'id')->andReturn(array());
		$presenter->shouldReceive('table')->once()->andReturn($table)
			->shouldReceive('actions')->once()->with($table)->andReturn('list.users');

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
		Orchestra::shouldReceive('make')->once()->with('orchestra.role')->andReturn($role);
		View::shouldReceive('make')->once()
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
		list($presenter, ) = $this->bindDependencies();

		$presenter->shouldReceive('form')->once()->andReturn('form.users');

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn(array());
		View::shouldReceive('make')->once()
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

		list($presenter, ) = $this->bindDependencies();

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$presenter->shouldReceive('form')->once()->andReturn('form.users');

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($builder);
		View::shouldReceive('make')->once()
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

		list(, $validator) = $this->bindDependencies();

		$user = m::mock('\Orchestra\Model\User');
		$auth = (object) array(
			'id' => 'foobar',
		);

		$user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andReturn(null);
		$validator->shouldReceive('on')->once()->with('create')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(null);

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);
		DB::shouldReceive('transaction')->once()
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

		list(, $validator) = $this->bindDependencies();

		$user = m::mock('\Orchestra\Model\User');
		$auth = (object) array(
			'id' => 'foobar',
		);

		$user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andThrow('\Exception');
		$validator->shouldReceive('on')->once()->with('create')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(null);

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
		DB::shouldReceive('transaction')->once()
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

		list(, $validator) = $this->bindDependencies();

		$user = m::mock('\Orchestra\Model\User');
		$auth = (object) array(
			'id' => 'foobar',
		);

		$validator->shouldReceive('on')->once()->with('create')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(true);

		Orchestra::shouldReceive('handles')->once()->with('orchestra::users/create')->andReturn('users/create');

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

		list(, $validator) = $this->bindDependencies();

		$builder = m::mock('UserModelBuilder');
		$user    = m::mock('\Orchestra\Model\User');
		$auth    = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andReturn(null);
		$validator->shouldReceive('on')->once()->with('update')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(null);

		Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($builder);
		Orchestra::shouldReceive('handles')->once()->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);
		DB::shouldReceive('transaction')->once()
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

		list(, $validator) = $this->bindDependencies();

		$builder = m::mock('UserModelBuilder');
		$user    = m::mock('\Orchestra\Model\User');
		$auth    = (object) array(
			'id' => 'foobar',
		);

		$builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
		$user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
			->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
			->shouldReceive('save')->once()->andReturn(null)
			->shouldReceive('roles')->once()->andReturn($user)
			->shouldReceive('sync')->once()->with($input['roles'])->andThrow('\Exception');
		$validator->shouldReceive('on')->once()->with('update')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(null);

		Orchestra::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);
		DB::shouldReceive('transaction')->once()
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

		list(, $validator) = $this->bindDependencies();
		
		$validator->shouldReceive('on')->once()->with('update')->andReturn($validator)
			->shouldReceive('with')->once()->with($input)->andReturn($validator)
			->shouldReceive('fails')->once()->andReturn(true);

		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::users/foo/edit')->andReturn('users/foo/edit');

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

		Orchestra::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()
			->with('success', m::any())->andReturn(null);
		Auth::shouldReceive('user')->once()->andReturn($auth);
		DB::shouldReceive('transaction')->once()
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

		Orchestra::shouldReceive('make')->once()
			->with('orchestra.user')->andReturn($builder);
		Orchestra::shouldReceive('handles')->once()
			->with('orchestra::users')->andReturn('users');
		Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(null);
		Auth::shouldReceive('user')->once()->andReturn($auth);
		DB::shouldReceive('transaction')->once()
			->with(m::type('Closure'))->andReturnUsing(
				function ($c)
				{
					$c();
				});

		$this->call('GET', 'admin/users/foo/delete');
		$this->assertRedirectedTo('users');
	}
}
