<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\App as Orchestra;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Mail;
use Orchestra\Support\Facades\Messages;

class RegisterControllerTest extends TestCase
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
        $presenter = m::mock('\Orchestra\Foundation\Presenter\Account');
        $validator = m::mock('\Orchestra\Foundation\Validation\Account');

        App::instance('Orchestra\Foundation\Presenter\Account', $presenter);
        App::instance('Orchestra\Foundation\Validation\Account', $validator);

        return array($presenter, $validator);
    }

    /**
     * Test GET /admin/register
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $form = m::mock('FormGrid');
        $user = m::mock('\Orchestra\Model\User');

        list($presenter,) = $this->bindDependencies();

        $form->shouldReceive('extend')->once()->with(m::type('Closure'))->andReturnUsing(
                function ($c) use ($form)
                {
                    $c($form);
                });
        $presenter->shouldReceive('profileForm')->once()->andReturn($form);

        Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::register')->andReturn('register');
        View::shouldReceive('make')->once()
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

        $user   = m::mock('\Orchestra\Model\User');
        $memory = m::mock('Memory\Manager');
        $mailer = m::mock('Mailer');

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('register')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);
        $user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null)
            ->shouldReceive('save')->once()->andReturn(null)
            ->shouldReceive('roles')->once()->andReturn($user)
            ->shouldReceive('sync')->once()->with(m::any())->andReturn(null)
            ->shouldReceive('toArray')->once()->andReturn($input)
            ->shouldReceive('getAttribute')->once()->with('email')->andReturn($input['email'])
            ->shouldReceive('getAttribute')->once()->with('fullname')->andReturn($input['fullname']);
        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('foo');
        $mailer->shouldReceive('subject')->once()->andReturn(null)
            ->shouldReceive('to')->once()->andReturn(null);
        Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Orchestra::shouldReceive('memory')->once()->andReturn($memory);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');
        DB::shouldReceive('transaction')->once()
            ->with(m::type('Closure'))->andReturnUsing(
                function ($c)
                {
                    $c();
                });
        Mail::shouldReceive('push')->once()
            ->with('orchestra/foundation::email.credential.register', m::type('Array'), m::type('Closure'))
            ->andReturnUsing(function ($v, $d, $c) use ($mailer)
                {
                    $c($mailer);
                    return array('email@orchestraplatform.com');
                });
        Messages::shouldReceive('add')->twice()
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

        $user   = m::mock('\Orchestra\Model\User');
        $memory = m::mock('Memory\Manager');
        $mailer = m::mock('Mailer');

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('register')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);
        $user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null)
            ->shouldReceive('save')->once()->andReturn(null)
            ->shouldReceive('roles')->once()->andReturn($user)
            ->shouldReceive('sync')->once()->with(m::any())->andReturn(null)
            ->shouldReceive('toArray')->once()->andReturn($input)
            ->shouldReceive('getAttribute')->once()->with('email')->andReturn($input['email'])
            ->shouldReceive('getAttribute')->once()->with('fullname')->andReturn($input['fullname']);
        $memory->shouldReceive('get')->once()->with('site.name', 'Orchestra Platform')->andReturn('foo')
            ->shouldReceive('get')->once()->with('email.queue', false)->andReturn(false);
        $mailer->shouldReceive('subject')->once()->andReturn(null)
            ->shouldReceive('to')->once()->andReturn(null);
        Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Orchestra::shouldReceive('memory')->once()->andReturn($memory);
        Orchestra::shouldReceive('handles')->once()
            ->with('orchestra::login')->andReturn('login');
        DB::shouldReceive('transaction')->once()
            ->with(m::type('Closure'))->andReturnUsing(
                function ($c)
                {
                    $c();
                });
        Mail::shouldReceive('push')->once()
            ->with('orchestra/foundation::email.credential.register', m::type('Array'), m::type('Closure'))
            ->andReturnUsing(function ($v, $d, $c) use ($mailer)
                {
                    $c($mailer);
                    return array();
                });
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturn(null);
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

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

        $user = m::mock('\Orchestra\Model\User');
        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('register')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);
        $user->shouldReceive('setAttribute')->once()->with('email', $input['email'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('fullname', $input['fullname'])->andReturn(null)
            ->shouldReceive('setAttribute')->once()->with('password', m::any())->andReturn(null);

        Orchestra::shouldReceive('make')->once()->with('orchestra.user')->andReturn($user);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::register')->andReturn('register');
        DB::shouldReceive('transaction')->once()->with(m::type('Closure'))->andThrow('\Exception');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

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

        list(, $validator) = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('register')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);
        Orchestra::shouldReceive('handles')->once()->with('orchestra::register')->andReturn('register');

        $this->call('POST', 'admin/register', $input);
        $this->assertRedirectedTo('register');
    }
}
