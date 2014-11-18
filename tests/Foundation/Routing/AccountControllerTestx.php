<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Testing\TestCase;

class AccountControllerTest extends TestCase
{

    /**
     * Test POST /admin/account/password
     *
     * @test
     */
    public function testPostPasswordAction()
    {
        $input = array(
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        );

        $user = m::mock('\Orchestra\Model\User');
        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('on')->once()->with('changePassword')->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring')
            ->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturnNull()
            ->shouldReceive('save')->once()->andReturnNull();

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()
            ->with($input['current_password'], 'hashedstring')->andReturn(true);
        DB::shouldReceive('transaction')->once()
            ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                $c();
            });
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::account/password', array())->andReturn('account/password');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('account/password');
    }

    /**
     * Test POST /admin/account/password with invalid user id.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testPostPasswordActionGivenInvalidUserId()
    {
        $input = array(
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        );

        $user = m::mock('\Orchestra\Model\User');
        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn(2);

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->call('POST', 'admin/account/password', $input);
    }

    /**
     * Test POST /admin/account/password with database error.
     *
     * @test
     */
    public function testPostPasswordActionGivenDatabaseError()
    {
        $input = array(
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        );

        $user = m::mock('\Orchestra\Model\User');
        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('on')->once()->with('changePassword')->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring')
            ->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturnNull()
            ->shouldReceive('save')->never()->andReturnNull();

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()
            ->with($input['current_password'], 'hashedstring')->andReturn(true);
        DB::shouldReceive('transaction')->once()
            ->with(m::type('Closure'))->andThrow('\Exception');
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::account/password', array())->andReturn('account/password');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

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
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        );

        $user = m::mock('\Orchestra\Model\User');
        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('on')->once()->with('changePassword')->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('hashedstring');

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()
            ->with($input['current_password'], 'hashedstring')->andReturn(false);
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::account/password', array())->andReturn('account/password');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

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
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        );

        $user = m::mock('\Orchestra\Model\User');
        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('on')->once()->with('changePassword')->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id']);

        Auth::shouldReceive('user')->once()->andReturn($user);
        Foundation::shouldReceive('handles')->once()
            ->with('orchestra::account/password', array())->andReturn('account/password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('account/password');
        $this->assertSessionHasErrors();
    }
}
