<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Services\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Messages;

class ForgotControllerTest extends TestCase
{
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
        $validator = m::mock('\Orchestra\Foundation\Validation\Auth');

        App::instance('Orchestra\Foundation\Validation\Auth', $validator);

        return $validator;
    }

    /**
     * Test GET /admin/forgot
     *
     * @test
     */
    public function testGetIndexAction()
    {
        View::shouldReceive('make')->once()->with('orchestra/foundation::forgot.index')->andReturn('foo');

        $this->call('GET', 'admin/forgot');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/forgot
     *
     * @test
     */
    public function testPostIndexAction()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
        );

        $validator = $this->bindDependencies();
        $mailer    = m::mock('Mailer');
        $memory    = m::mock('Memory');
        $password  = m::mock('PasswordBroker');

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Orchestra::shouldReceive('memory')->once()->andReturn($memory);

        $mailer->shouldReceive('subject')->once()->andReturn(null);
        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('Orchestra Platform');

        Password::swap($password);

        $password->shouldReceive('remind')->once()->andReturnUsing(function ($d, $c) use ($mailer) {
            $c($mailer);

            return Password::REMINDER_SENT;
        });

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
    }

    /**
     * Test POST /admin/forgot given invalid user.
     *
     * @test
     */
    public function testPostIndexActionWhenInvalidUserIsGiven()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
        );

        $validator = $this->bindDependencies();
        $mailer    = m::mock('Mailer');
        $memory    = m::mock('Memory');
        $password  = m::mock('PasswordBroker');

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Orchestra::shouldReceive('memory')->once()->andReturn($memory);

        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('Orchestra Platform');

        Password::swap($password);

        $password->shouldReceive('remind')->once()->andReturn(Password::INVALID_USER);

        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
    }

    /**
     * Test POST /admin/forgot when validation fails
     *
     * @test
     */
    public function testPostIndexActionWhenValidationFail()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
        );

        $validator = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
        $this->assertSessionHas('errors');
    }

    /**
     * Test GET /admin/forgot/reset
     *
     * @test
     */
    public function testGetResetAction()
    {
        View::shouldReceive('make')->once()->with('orchestra/foundation::forgot.reset')->andReturn(m::self());
        View::shouldReceive('with')->once()->with('token', 'auniquetoken')->andReturn('foo');

        $this->call('GET', 'admin/forgot/reset/auniquetoken');
        $this->assertResponseOk();
    }

     /**
     * Test GET /admin/forgot/reset given token is not null.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetResetActionGivenTokenIsNull()
    {
        $this->call('GET', 'admin/forgot/reset/');
    }

    /**
     * Test POST /admin/forgot/reset
     *
     * @test
     */
    public function testPostResetAction()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => 'auniquetoken',
        );

        $password = m::mock('PasswordBroker');
        $user     = m::mock('\Orchestra\Model\User');

        $user->shouldReceive('setAttribute')->once()->with('password', '123456')->andReturn(null)
            ->shouldReceive('save')->once()->andReturn(null);

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturnUsing(function ($d, $c) use ($user) {
                $c($user, '123456');

                return Password::PASSWORD_RESET;
            });

        Password::swap($password);

        Auth::shouldReceive('login')->once()->with($user)->andReturn(null);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::/')->andReturn('dashboard');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('dashboard');
    }

    /**
     * Test POST /admin/forgot/reset given invalid password.
     *
     * @test
     */
    public function testPostResetActionWhenInvalidPassword()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
            'password' => '123456',
            'password_confirmation' => '654321',
            'token' => 'auniquetoken',
        );

        $password = m::mock('PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_PASSWORD);

        Password::swap($password);

        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }

    /**
     * Test POST /admin/forgot/reset given invalid token.
     *
     * @test
     */
    public function testPostResetActionWhenTokenIsInvalid()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => 'auniquetoken',
        );

        $password = m::mock('PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_TOKEN);

        Password::swap($password);

        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }

    /**
     * Test POST /admin/forgot/reset given invalid user.
     *
     * @test
     */
    public function testPostResetActionWhenUserIsInvalid()
    {
        $input = array(
            'email' => 'email@orchestraplatform.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => 'auniquetoken',
        );

        $password = m::mock('PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_USER);

        Password::swap($password);

        Orchestra::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }
}
