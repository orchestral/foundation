<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Illuminate\Support\Facades\Password;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Testing\TestCase;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordBrokerControllerTest extends TestCase
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
        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');
        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        View::shouldReceive('make')->once()->with('orchestra/foundation::forgot.index')->andReturn('foo');
        View::shouldReceive('share')->once()->with('errors', m::any());

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
        $mailer    = m::mock('\Orchestra\Notifier\Mailer');
        $memory    = m::mock('\Orchestra\Memory\Provider')->makePartial();
        $password  = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('memory')->once()->andReturn($memory);

        $mailer->shouldReceive('subject')->once()->andReturnNull();
        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')
            ->andReturn('Orchestra Platform');

        $password->shouldReceive('sendResetLink')->once()->andReturnUsing(function ($d, $c) use ($mailer) {
            $c($mailer);

            return PasswordBroker::RESET_LINK_SENT;
        });

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

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
        $mailer    = m::mock('\Orchestra\Notifier\Mailer');
        $memory    = m::mock('\Orchestra\Memory\Provider')->makePartial();
        $password  = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Foundation::shouldReceive('memory')->once()->andReturn($memory);

        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')
            ->andReturn('Orchestra Platform');

        $password->shouldReceive('sendResetLink')->once()->andReturn(Password::INVALID_USER);

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

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

        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');
        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        $validator = $this->bindDependencies();

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot')->andReturn('forgot');

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
        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');
        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        $factory = m::mock('\Illuminate\Contracts\View\Factory');
        $view = m::mock('\Illuminate\Contracts\View\View');

        $factory->shouldReceive('make')->once()->with('orchestra/foundation::forgot.reset')->andReturn($view)
            ->shouldReceive('share')->with('errors', m::any());
        $view->shouldReceive('with')->once()->with('token', 'auniquetoken')->andReturn('foo');

        View::swap($factory);

        $this->call('GET', 'admin/forgot/reset/auniquetoken');
        $this->assertResponseOk();
    }

     /**
     * Test GET /admin/forgot/reset given token is not null.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function testGetResetActionGivenTokenIsNull()
    {
        $this->call('GET', 'admin/forgot/reset');
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

        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');
        $user     = m::mock('\Orchestra\Model\User');

        $user->shouldReceive('setAttribute')->once()->with('password', '123456')->andReturnNull()
            ->shouldReceive('save')->once()->andReturnNull();

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturnUsing(function ($d, $c) use ($user) {
                $c($user, '123456');

                return PasswordBroker::PASSWORD_RESET;
            });

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Auth::shouldReceive('login')->once()->with($user)->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::/')->andReturn('dashboard');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

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

        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_PASSWORD);

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

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

        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_TOKEN);

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

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

        $password = m::mock('\Orchestra\Auth\Passwords\PasswordBroker');

        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturn(Password::INVALID_USER);

        App::instance('Illuminate\Contracts\Auth\PasswordBroker', $password);

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken')->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }
}
