<?php

namespace Orchestra\Tests\Unit\Http\Controllers;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UsersControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();

        $this->app['Illuminate\Contracts\Auth\Guard'] = $auth = m::mock('\Illuminate\Contracts\Auth\Guard');
        $this->app['Illuminate\Contracts\Auth\Authenticatable'] = $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $auth->shouldReceive('user')->andReturn($user);
    }

    /**
     * Bind dependencies.
     *
     * @return array
     */
    protected function bindDependencies()
    {
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\User');
        $validator = m::mock('\Orchestra\Foundation\Validation\User');

        App::instance('Orchestra\Foundation\Http\Presenters\User', $presenter);
        App::instance('Orchestra\Foundation\Validation\User', $validator);

        return [$presenter, $validator];
    }

    /**
     * Test GET /admin/users.
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $user = m::mock('\Orchestra\Model\User');
        $role = m::mock('\Orchestra\Model\Role');
        $table = m::mock('\Orchestra\Contracts\Html\Table\Builder');

        list($presenter) = $this->bindDependencies();

        $user->shouldReceive('search')->once()->with('', [])->andReturn($user);
        $role->shouldReceive('pluck')->once()->with('name', 'id')->andReturn([]);
        $presenter->shouldReceive('table')->once()->andReturn($table)
            ->shouldReceive('actions')->once()->with($table)->andReturn('list.users');

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Foundation::shouldReceive('make')->once()->with('orchestra.role')->andReturn($role);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::users.index', m::type('Array'), [])->andReturn('foo');

        $this->call('GET', 'admin/users');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/users/create.
     *
     * @test
     */
    public function testGetCreateAction()
    {
        list($presenter) = $this->bindDependencies();

        $presenter->shouldReceive('form')->once()->andReturn('form.users');

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn([]);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::users.edit', m::type('Array'), [])->andReturn('foo');

        $this->call('GET', 'admin/users/create');
        $this->assertResponseOk();
    }

    /**
     * Test GET /admin/users/(:any)/edit.
     *
     * @test
     */
    public function testGetEditAction()
    {
        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');

        list($presenter) = $this->bindDependencies();

        $builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
        $presenter->shouldReceive('form')->once()->andReturn('form.users');

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($builder);
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::users.edit', m::type('Array'), [])->andReturn('foo');

        $this->call('GET', 'admin/users/foo/edit');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/users.
     *
     * @test
     */
    public function testPostStoreAction()
    {
        $input = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $user = m::mock('\Orchestra\Model\User');

        $user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturnNull()
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('save')->once()->andReturnNull()
            ->shouldReceive('roles->sync')->once()->with($input['roles'])->andReturnNull();
        $validator->shouldReceive('on')->once()->with('create')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Foundation::shouldReceive('handles')->once()->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnSelf();

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
        $input = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $user = m::mock('\Orchestra\Model\User');

        $user->shouldReceive('setAttribute')->once()->with('status', 0)->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturnNull()
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('save')->once()->andReturnNull()
            ->shouldReceive('roles->sync')->once()->with($input['roles'])->andThrow('\Exception');
        $validator->shouldReceive('on')->once()->with('create')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Foundation::shouldReceive('handles')->once()->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnSelf();

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
        $input = [
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $validator->shouldReceive('on')->once()->with('create')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('getMessageBag')->once()->andReturn([])
            ->shouldReceive('fails')->once()->andReturn(true);

        Foundation::shouldReceive('handles')->once()->with('orchestra::users/create', [])->andReturn('users/create');

        $this->call('POST', 'admin/users', $input);
        $this->assertRedirectedTo('users/create');
        $this->assertSessionHasErrors();
    }

    /**
     * Test PUT /admin/users/(:any).
     *
     * @test
     */
    public function testPutUpdateAction()
    {
        $input = [
            'id' => 'foo',
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');

        $builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
        $user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturnNull()
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('save')->once()->andReturnNull()
            ->shouldReceive('roles->sync')->once()->with($input['roles'])->andReturnNull();
        $validator->shouldReceive('on')->once()->with('update')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($builder);
        Foundation::shouldReceive('handles')->once()->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnSelf();

        $this->call('PUT', 'admin/users/foo', $input);
        $this->assertRedirectedTo('users');
    }

    /**
     * Test PUT /admin/users/(:any) when invalid user id is given.
     *
     * @test
     */
    public function testPutUpdateActionGivenInvalidUserId()
    {
        $input = [
            'id' => 'foo',
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        $this->call('PUT', 'admin/users/foobar', $input);
        $this->assertResponseStatus(500);
    }

    /**
     * Test PUT /admin/users/(:any) when database error.
     *
     * @test
     */
    public function testPutUpdateActionGivenDatabaseError()
    {
        $input = [
            'id' => 'foo',
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');

        $builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
        $user->shouldReceive('setAttribute')->once()->with('password', $input['password'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturnNull()
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('save')->once()->andReturnNull()
            ->shouldReceive('roles->sync')->once()->with($input['roles'])->andThrow('\Exception');
        $validator->shouldReceive('on')->once()->with('update')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('fails')->once()->andReturnNull();

        Foundation::shouldReceive('make')->once()
            ->with('orchestra.user')->andReturn($builder);
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()
            ->with('error', m::any())->andReturnSelf();

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
        $input = [
            'id' => 'foo',
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
            'password' => '123456',
            'roles' => [1],
        ];

        list(, $validator) = $this->bindDependencies();
        $validation = m::mock('\Illuminate\Contracts\Validation\Validator');

        $validator->shouldReceive('on')->once()->with('update')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($validation);

        $validation->shouldReceive('getMessageBag')->once()->andReturn([])
            ->shouldReceive('fails')->once()->andReturn(true);

        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::users/foo/edit', [])->andReturn('users/foo/edit');

        $this->call('PUT', 'admin/users/foo', $input);
        $this->assertRedirectedTo('users/foo/edit');
        $this->assertSessionHasErrors();
    }

    /**
     * Test GET /admin/users/(:any)/delete.
     *
     * @test
     */
    public function testGetDeleteAction()
    {
        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');
        $auth = (object) [
            'id' => 'foobar',
        ];

        $builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn('foo')
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('delete')->once()->andReturnNull();

        Foundation::shouldReceive('make')->once()
            ->with('orchestra.user')->andReturn($builder);
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()
            ->with('success', m::any())->andReturnNull();
        Auth::shouldReceive('user')->once()->andReturn($auth);

        $this->call('GET', 'admin/users/foo/delete');
        $this->assertRedirectedTo('users');
    }

    /**
     * Test GET /admin/users/(:any)/delete when trying to delete own
     * account.
     *
     * @test
     */
    public function testGetDeleteActionWhenDeletingOwnAccount()
    {
        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');
        $auth = (object) [
            'id' => 'foobar',
        ];

        $builder->shouldReceive('findOrFail')->once()->with('foobar')->andReturn($user);
        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn('foobar');

        Foundation::shouldReceive('make')->once()->with('orchestra.user')->andReturn($builder);
        Auth::shouldReceive('user')->once()->andReturn($auth);

        $this->call('GET', 'admin/users/foobar/delete');
        $this->assertResponseStatus(404);
    }

    /**
     * Test GET /admin/users/(:any)/delete when database error.
     *
     * @test
     */
    public function testGetDeleteActionGivenDatabaseError()
    {
        $builder = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial();
        $user = m::mock('\Orchestra\Model\User');
        $auth = (object) [
            'id' => 'foobar',
        ];

        $builder->shouldReceive('findOrFail')->once()->with('foo')->andReturn($user);
        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn('foo')
            ->shouldReceive('transaction')->once()
                ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                    $c();
                });
        $user->shouldReceive('delete')->once()->andThrow('\Exception');

        Foundation::shouldReceive('make')->once()
            ->with('orchestra.user')->andReturn($builder);
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::users', [])->andReturn('users');
        Messages::shouldReceive('add')->once()
            ->with('error', m::any())->andReturnNull();
        Auth::shouldReceive('user')->once()->andReturn($auth);

        $this->call('GET', 'admin/users/foo/delete');
        $this->assertRedirectedTo('users');
    }
}
