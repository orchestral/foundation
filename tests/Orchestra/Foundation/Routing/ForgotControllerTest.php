<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
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
        $mailer = m::mock('Mailer');

        Orchestra::shouldReceive('memory')->once()->andReturn($memory = m::mock('Memory'));
        Password::swap($password = m::mock('PasswordBroker'));

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);
        $mailer->shouldReceive('subject')->once()->andReturn(null);
        $password->shouldReceive('remind')->once()->andReturnUsing(function ($d, $c) use ($mailer) {
            $c($mailer);
        });
        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('Orchestra Platform');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertResponseOk();
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

        Orchestra::shouldReceive('handles')->once()
            ->with('orchestra::forgot')->andReturn('forgot');

        $validator->shouldReceive('with')->once()->with(m::type('Array'))->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

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
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::forgot.reset')->andReturn(m::self());
        View::shouldReceive('with')->once()
            ->with('token', 'auniquetoken')->andReturn('foo');

        $this->call('GET', 'admin/forgot/reset/auniquetoken');
        $this->assertResponseOk();
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
        );

        $password = m::mock('PasswordBroker');
        $user     = m::mock('\Orchestra\Model\User');

        $user->shouldReceive('setAttribute')->once()->with('password', 'foo')->andReturn(null)
            ->shouldReceive('save')->once()->andReturn(null);
        $password->shouldReceive('reset')->once()->with($input, m::type('Closure'))
            ->andReturnUsing(function ($d, $c) use ($user) {
                return $c($user, 'foo');
            });

        Password::swap($password);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);

        $this->call('POST', 'admin/forgot/reset/auniquetoken', $input);
        $this->assertRedirectedTo('login');
    }
}
