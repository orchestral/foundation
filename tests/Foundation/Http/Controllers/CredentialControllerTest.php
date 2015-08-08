<?php namespace Orchestra\Foundation\Http\Controllers\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Orchestra\Foundation\Auth\WithoutThrottle;

class CredentialControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Test GET /admin/login.
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
     * Test POST /admin/login.
     *
     * @test
     */
    public function testPostLoginAction()
    {
        $input = [
            'email' => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        list($authenticate, $deauthenticate) = $this->getMockedProcessor();

        $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $authenticate->shouldReceive('login')->once()
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
            'email' => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        list($authenticate, $deauthenticate) = $this->getMockedProcessor();

        $user = m::mock('\Illuminate\Contracts\Auth\Authenticatable');

        $authenticate->shouldReceive('login')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'), m::type('Array'), m::type(WithoutThrottle::class))
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
            'email' => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        ];

        list($authenticate, $deauthenticate) = $this->getMockedProcessor();

        $authenticate->shouldReceive('login')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'), m::type('Array'), m::type(WithoutThrottle::class))
            ->andReturnUsing(function ($listener) {
                return $listener->userLoginHasFailedValidation([]);
            });
        Foundation::shouldReceive('handles')->once()->with('orchestra::login', [])->andReturn('login');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('login');
        $this->assertSessionHasErrors();
    }

    /**
     * Test GET /admin/logout.
     *
     * @test
     */
    public function testDeleteLoginAction()
    {
        list($authenticate, $deauthenticate) = $this->getMockedProcessor();

        $deauthenticate->shouldReceive('logout')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\CredentialController'))
            ->andReturnUsing(function ($listener) {
                return $listener->userHasLoggedOut();
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::login', [])->andReturn('login');

        $this->call('GET', 'admin/logout');
        $this->assertRedirectedTo('login');
    }

    /**
     * Test GET /admin/logout?redirect=home.
     *
     * @test
     */
    public function testDeleteLoginActionWithRedirection()
    {
        list($authenticate, $deauthenticate) = $this->getMockedProcessor();

        $deauthenticate->shouldReceive('logout')->once()
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
    protected function getMockedProcessor()
    {
        $throttles = new WithoutThrottle();
        $validation = m::mock('\Orchestra\Foundation\Validation\AuthenticateUser');
        $auth = m::mock('\Orchestra\Contracts\Auth\Guard');

        $authenticate = m::mock('\Orchestra\Foundation\Processor\AuthenticateUser', [$auth, $validation]);
        $deauthenticate = m::mock('\Orchestra\Foundation\Processor\DeauthenticateUser', [$auth]);

        $this->app->instance('Orchestra\Foundation\Processor\AuthenticateUser', $authenticate);
        $this->app->instance('Orchestra\Foundation\Processor\DeauthenticateUser', $deauthenticate);
        $this->app->instance('Orchestra\Contracts\Auth\Command\ThrottlesLogins', $throttles);

        return [$authenticate, $deauthenticate];
    }
}
