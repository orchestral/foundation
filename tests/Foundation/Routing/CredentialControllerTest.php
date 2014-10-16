<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\App;
use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\Auth;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Testing\TestCase;

class CredentialControllerTest extends TestCase
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
     * Test GET /admin/login
     *
     * @test
     */
    public function testGetLoginAction()
    {
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
        $input = array(
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        );

        $user = m::mock('\Orchestra\Model\User');
        $validator = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        $user->shouldReceive('getAttribute')->once()->with('status')->andReturn(0)
            ->shouldReceive('activate')->once()->andReturnNull()
            ->shouldReceive('save')->once()->andReturnNull();

        Auth::shouldReceive('attempt')->once()->with(m::type('Array'), true)->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($user);
        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();

        Foundation::shouldReceive('handles')->once()->with('orchestra::/')->andReturn('/');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('/');
    }

    /**
     * Test POST /admin/login when authentication failed.
     *
     * @test
     */
    public function testPostLoginActionGivenAuthenticationFails()
    {
        $input = array(
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        );

        $validator = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(false);

        Auth::shouldReceive('attempt')->once()
            ->with(m::type('Array'), true)->andReturn(false);
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

        Foundation::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

        $this->call('POST', 'admin/login', $input);
        $this->assertRedirectedTo('login');
    }

    /**
     * Test POST /admin/login when validation failed.
     *
     * @test
     */
    public function testPostLoginActionGivenValidationFails()
    {
        $input = array(
            'email'    => 'hello@orchestraplatform.com',
            'password' => '123456',
            'remember' => 'yes',
        );

        $validator = $this->bindDependencies();

        $validator->shouldReceive('on')->once()->with('login')->andReturn($validator)
            ->shouldReceive('with')->once()->with($input)->andReturn($validator)
            ->shouldReceive('fails')->once()->andReturn(true);

        Foundation::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

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
        Auth::shouldReceive('logout')->once()->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::login')->andReturn('login');

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
        Auth::shouldReceive('logout')->once()->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('home')->andReturn('home');

        $this->call('GET', 'admin/logout', array('redirect' => 'home'));
        $this->assertRedirectedTo('home');
    }
}
