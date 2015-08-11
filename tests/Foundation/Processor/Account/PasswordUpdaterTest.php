<?php namespace Orchestra\Foundation\Processor\Account\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Orchestra\Foundation\Processor\Account\PasswordUpdater;

class PasswordUpdaterTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::edit()
     * method.
     *
     * @test
     */
    public function testEditMethod()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $stub = new PasswordUpdater($presenter, $validator);

        $presenter->shouldReceive('password')->once()->with($user)->andReturnSelf();
        $listener->shouldReceive('showPasswordChanger')->once()
            ->with(['eloquent' => $user, 'form' => $presenter])->andReturn('show.password.changer');

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->assertEquals('show.password.changer', $stub->edit($listener));
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::update()
     * method.
     *
     * @test
     */
    public function testUpdateMethod()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $resolver = m::mock('\Illuminate\Contracts\Validation\Validator');
        $user = m::mock('\Orchestra\Model\User, \Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $stub = new PasswordUpdater($presenter, $validator);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('old.password')
            ->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturnNull()
            ->shouldReceive('save')->once()->andReturnNull();
        $validator->shouldReceive('on')->once()->with('changePassword')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(false);
        $listener->shouldReceive('passwordUpdated')->once()->andReturn('password.updated');

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()->with($input['current_password'], 'old.password')->andReturn(true);
        DB::shouldReceive('transaction')->once()
            ->with(m::type('Closure'))->andReturnUsing(function ($c) {
                $c();
            });

        $this->assertEquals('password.updated', $stub->update($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::update()
     * method given user mismatched.
     *
     * @test
     */
    public function testUpdateMethodGivenUserMissmatched()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $stub = new PasswordUpdater($presenter, $validator);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id']++);
        $listener->shouldReceive('abortWhenUserMismatched')->once()->andReturn('user.missmatched');

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->assertEquals('user.missmatched', $stub->update($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::update()
     * method given validation failed.
     *
     * @test
     */
    public function testUpdateMethodGivenValidationFailed()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $resolver = m::mock('\Illuminate\Contracts\Validation\Validator');
        $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $stub = new PasswordUpdater($presenter, $validator);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id']);
        $validator->shouldReceive('on')->once()->with('changePassword')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(true)
            ->shouldReceive('getMessageBag')->once()->andReturn([]);
        $listener->shouldReceive('updatePasswordFailedValidation')->once()
            ->with([])->andReturn('password.failed.validation');

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->assertEquals('password.failed.validation', $stub->update($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::update()
     * method given saving failed.
     *
     * @test
     */
    public function testUpdateMethodGivenSavingFailed()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $resolver = m::mock('\Illuminate\Contracts\Validation\Validator');
        $user = m::mock('\Orchestra\Model\User, \Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $stub = new PasswordUpdater($presenter, $validator);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('old.password')
            ->shouldReceive('setAttribute')->once()->with('password', $input['new_password'])->andReturnNull();
        $validator->shouldReceive('on')->once()->with('changePassword')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(false);
        $listener->shouldReceive('updatePasswordFailed')->once()->with(m::type('Array'))->andReturn('password.failed');

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()->with($input['current_password'], 'old.password')->andReturn(true);
        DB::shouldReceive('transaction')->once()->with(m::type('Closure'))->andThrow('\Exception');

        $this->assertEquals('password.failed', $stub->update($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\Account\ProfileUpdater::update()
     * method given current password missmatch.
     *
     * @test
     */
    public function testUpdateMethodGivenCurrentPasswordMissmatch()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater');
        $presenter = m::mock('\Orchestra\Foundation\Http\Presenters\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');
        $resolver = m::mock('\Illuminate\Contracts\Validation\Validator');
        $user = m::mock('\Orchestra\Model\User, \Illuminate\Contracts\Auth\Authenticatable');

        $input = $this->getInput();

        $stub = new PasswordUpdater($presenter, $validator);

        $user->shouldReceive('getAttribute')->once()->with('id')->andReturn($input['id'])
            ->shouldReceive('getAttribute')->once()->with('password')->andReturn('old.password');
        $validator->shouldReceive('on')->once()->with('changePassword')->andReturnSelf()
            ->shouldReceive('with')->once()->with($input)->andReturn($resolver);
        $resolver->shouldReceive('fails')->once()->andReturn(false);
        $listener->shouldReceive('verifyCurrentPasswordFailed')->once()->andReturn('current.password.failed');

        Auth::shouldReceive('user')->once()->andReturn($user);
        Hash::shouldReceive('check')->once()->with($input['current_password'], 'old.password')->andReturn(false);

        $this->assertEquals('current.password.failed', $stub->update($listener, $input));
    }

    /**
     * Get sample input.
     *
     * @return array
     */
    protected function getInput()
    {
        return [
            'id' => '1',
            'current_password' => '123456',
            'new_password' => 'qwerty',
        ];
    }
}
