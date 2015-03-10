<?php namespace Orchestra\Foundation\Http\Controllers\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;

class CredentialControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->once()->with('errors', m::any());
    }

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
    protected function bindValidation()
    {
        $validator = m::mock('\Orchestra\Foundation\Validation\AuthenticateUser');

        $this->app->instance('Orchestra\Foundation\Validation\AuthenticateUser', $validator);

        return $validator;
    }

    /**
     * Test GET /admin/login
     *
     * @test
     */
    public function testGetLoginAction()
    {
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::credential.login', [], [])->andReturn('foo');

        $this->call('GET', 'admin/login');

        $this->assertResponseOk();
        $this->assertTrue(Meta::has('title'));
    }

    /**
     * Test POST /admin/login
     *
     * @test
     */
    public function testPostLoginAction()
    {
        $input = [
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        $processor = $this->getProcessorMock();
        $user      = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $processor->shouldReceive('login')->once()
            ->andReturnUsing(function ($listener) use ($user) {
                return $listener->userHasLoggedIn($user);
            });

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::/', [])->andReturn('/');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('/');
    }

    /**
     * Test POST /admin/login when authentication failed.
     *
     * @test
     */
    public function testPostLoginActionGivenFailedAuthentication()
    {
        $input = [
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        $processor = $this->getProcessorMock();
        $user      = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $processor->shouldReceive('login')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'), m::type('Array'))
            ->andReturnUsing(function ($listener, $input) use ($user) {
                return $listener->userLoginHasFailedAuthentication($input);
            });

        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::login', [])->andReturn('login');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('login');
    }

    /**
     * Test POST /admin/login when validation failed.
     *
     * @test
     */
    public function testPostLoginActionGivenFailedValidation()
    {
        $input = [
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        $this->getProcessorMock()->shouldReceive('login')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'), m::type('Array'))
            ->andReturnUsing(function ($listener) {
                return $listener->userLoginHasFailedValidation([]);
            });
        Foundation::shouldReceive('handles')->once()->with('orchestra::login', [])->andReturn('login');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('login');
        $this->assertSessionHasErrors();
    }

    /**
     * Test GET /admin/logout
     *
     * @test
     */
    public function testDeleteLoginAction()
    {
        $this->getProcessorMock()->shouldReceive('logout')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'))
            ->andReturnUsing(function ($listener) {
                return $listener->userHasLoggedOut();
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::login', [])->andReturn('login');

        $this->call('GET', 'admin/logout');
        $this->assertRedirectedTo('login');
    }

    /**
     * Test GET /admin/logout?redirect=home
     *
     * @test
     */
    public function testDeleteLoginActionWithRedirection()
    {
        $this->getProcessorMock()->shouldReceive('logout')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'))
            ->andReturnUsing(function ($listener) {
                return $listener->userHasLoggedOut();
            });

        Foundation::shouldReceive('handles')->once()->with('home', [])->andReturn('home');

        $this->call('GET', 'admin/logout', ['redirect' => 'home']);
        $this->assertRedirectedTo('home');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\AuthenticateUser
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\AuthenticateUser', [
            m::mock('\Orchestra\Foundation\Validation\AuthenticateUser'),
            m::mock('\Illuminate\Contracts\Auth\Guard')
        ]);

        $this->app->instance('Orchestra\Foundation\Processor\AuthenticateUser', $processor);

        return $processor;
    }
}
