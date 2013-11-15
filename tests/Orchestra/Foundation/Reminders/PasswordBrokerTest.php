<?php namespace Orchestra\Foundation\Reminders\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Reminders\PasswordBroker;

class PasswordBrokerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $app = new Container;
        $app['translator'] = $translator = m::mock('Translator');
        $translator->shouldReceive('trans')->andReturn('foo');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::remind() method.
     *
     * @test
     */
    public function testRemindMethod()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $callback = function () {
            //
        };

        $user->shouldReceive('retrieveByCredentials')->once()
            ->with(array('username' => 'user-foo'))
            ->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
        $reminders->shouldReceive('create')->once()->with($userReminderable)->andReturn(null);
        $userReminderable->shouldReceive('getReminderEmail')->once()->andReturn('foo@reminderable.com');
        $mailer->shouldReceive('to')->once()->with('foo@reminderable.com')->andReturn(null)
            ->shouldReceive('push')->once()->with('foo', m::any(), m::type('Closure'))
                ->andReturnUsing(function ($v, $d, $c) use ($mailer) {
                    $c($mailer);
                });

        $this->assertEquals('reminders.sent', $stub->remind(array('username' => 'user-foo')));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::remind() given
     * user is null.
     *
     * @test
     */
    public function testRemindMethodGivenUserIsNull()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $user->shouldReceive('retrieveByCredentials')->once()
            ->with(array('username' => 'user-foo'))->andReturn(null);

        $this->assertEquals('reminders.user', $stub->remind(array('username' => 'user-foo')));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method.
     *
     * @test
     */
    public function testResetMethod()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $callback = function ($user, $pass) {
            return 'foo';
        };

        $credentials = array(
            'username' => 'user-foo',
            'password' => 'qwerty',
            'password_confirmation' => 'qwerty',
            'token' => 'someuniquetokenkey',
        );

        $user->shouldReceive('retrieveByCredentials')->once()
                ->with(array_except($credentials, array('password_confirmation', 'token')))
                ->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
        $reminders->shouldReceive('exists')->once()->with($userReminderable, 'someuniquetokenkey')->andReturn(true)
            ->shouldReceive('delete')->once()->with('someuniquetokenkey')->andReturn(true);

        $this->assertEquals('reminders.reset', $stub->reset($credentials, $callback));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method
     * given user ins not \Illuminate\Auth\Reminders\RemindableInteface.
     *
     * @test
     */
    public function testResetMethodGivenUserIsNotRemindableInterface()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $callback = function ($user, $pass) {
            //
        };

        $credentials = array(
            'username' => 'user-foo',
            'password' => 'qwerty',
            'password_confirmation' => 'qwerty',
            'token' => 'someuniquetokenkey',
        );

        $user->shouldReceive('retrieveByCredentials')->once()
            ->with(array_except($credentials, array('password_confirmation', 'token')))->andReturn(null);

        $this->assertEquals('reminders.user', $stub->reset($credentials, $callback));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method
     * given fail verify password.
     *
     * @test
     */
    public function testResetMethodGivenFailVerifyPassword()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $callback = function ($user, $pass) {
            //
        };

        $credentials = array(
            'username' => 'user-foo',
            'password' => 'qwerty',
            'password_confirmation' => 'qwerty',
            'token' => 'someuniquetokenkey',
        );

        $user->shouldReceive('retrieveByCredentials')->once()
                ->with(array_except($credentials, array('password_confirmation', 'token')))
                ->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
        $reminders->shouldReceive('exists')->once()->with($userReminderable, 'someuniquetokenkey')->andReturn(false);

        $this->assertEquals('reminders.token', $stub->reset($credentials, $callback));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::reset() method
     * given reminder not exist.
     *
     * @test
     */
    public function testResetMethodGivenReminderNotExist()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $callback = function ($user, $pass) {
            //
        };

        $credentials = array(
            'username' => 'user-foo',
            'password' => 'qwerty',
            'password_confirmation' => 'qwerty',
            'token' => 'someuniquetokenkey',
        );

        $user->shouldReceive('retrieveByCredentials')->once()
                ->with(array_except($credentials, array('password_confirmation', 'token')))
                ->andReturn($userReminderable = m::mock('\Illuminate\Auth\Reminders\RemindableInterface'));
        $reminders->shouldReceive('exists')->once()->with($userReminderable, 'someuniquetokenkey')->andReturn(false);

        $this->assertEquals('reminders.token', $stub->reset($credentials, $callback));
    }

    /**
     * Test Orchestra\Foundation\Reminders\PasswordBroker::getUser() method
     * throws exception.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testGetUserThrowsException()
    {
        $stub = new PasswordBroker(
            $reminders = m::mock('\Illuminate\Auth\Reminders\ReminderRepositoryInterface'),
            $user = m::mock('\Illuminate\Auth\UserProviderInterface'),
            $mailer = m::mock('\Orchestra\Foundation\Mail'),
            $view = 'foo'
        );

        $user->shouldReceive('retrieveByCredentials')->once()->with(array())->andReturn('foo');

        $stub->getUser(array());
    }
}
