<?php namespace Orchestra\Foundation\Http\Controllers\Account\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordBrokerControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->with('errors', m::any());
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
     * Test GET /admin/forgot
     *
     * @test
     */
    public function testGetCreateAction()
    {
        $this->getProcessorMock();

        View::shouldReceive('make')->once()->with('orchestra/foundation::forgot.index', [], [])->andReturn('foo');

        $this->call('GET', 'admin/forgot');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/forgot
     *
     * @test
     */
    public function testPostStoreAction()
    {
        $input = [
            'email' => 'email@orchestraplatform.com',
        ];

        $this->getProcessorMock()->shouldReceive('store')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->resetLinkSent(PasswordBroker::RESET_LINK_SENT);
            });

        Messages::shouldReceive('add')->once()->with('success', trans(PasswordBroker::RESET_LINK_SENT))->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot', [])->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
    }

    /**
     * Test POST /admin/forgot given invalid user.
     *
     * @test
     */
    public function testPostStoreActionGivenInvalidUser()
    {
        $input = [
            'email' => 'email@orchestraplatform.com',
        ];

        $this->getProcessorMock()->shouldReceive('store')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->resetLinkFailed(PasswordBroker::INVALID_USER);
            });

        Messages::shouldReceive('add')->once()->with('error', trans(PasswordBroker::INVALID_USER))->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot', [])->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
    }

    /**
     * Test POST /admin/forgot when validation fails
     *
     * @test
     */
    public function testPostStoreActionGivenFailedValidation()
    {
        $input = [
            'email' => 'email@orchestraplatform.com',
        ];

        $this->getProcessorMock()->shouldReceive('store')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->resetLinkFailedValidation([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot', [])->andReturn('forgot');

        $this->call('POST', 'admin/forgot', $input);
        $this->assertRedirectedTo('forgot');
        $this->assertSessionHas('errors');
    }

    /**
     * Test GET /admin/forgot/reset
     *
     * @test
     */
    public function testGetEditAction()
    {
        $this->getProcessorMock();

        $view = m::mock('\Illuminate\Contracts\View\View');

        View::shouldReceive('make')->once()->with('orchestra/foundation::forgot.reset', [], [])->andReturn($view);
        $view->shouldReceive('with')->once()->with('token', 'auniquetoken')->andReturn('foo');

        $this->call('GET', 'admin/forgot/reset/auniquetoken');
        $this->assertResponseOk();
    }

     /**
     * Test GET /admin/forgot/reset given token is null.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function testGetEditActionGivenTokenIsNull()
    {
        $this->call('GET', 'admin/forgot/reset');
    }

    /**
     * Test POST /admin/forgot/reset
     *
     * @test
     */
    public function testPostUpdateAction()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordHasReset(PasswordBroker::PASSWORD_RESET);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::/', [])->andReturn('dashboard');
        Messages::shouldReceive('add')->once()->with('success', m::type('String'))->andReturnNull();

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('dashboard');
    }

    /**
     * Test POST /admin/forgot/reset given invalid password.
     *
     * @test
     */
    public function testPostUpdateActionGivenInvalidPassword()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordResetHasFailed(PasswordBroker::INVALID_PASSWORD);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken', [])->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', trans(PasswordBroker::INVALID_PASSWORD))->andReturnNull();

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }

    /**
     * Test POST /admin/forgot/reset given invalid token.
     *
     * @test
     */
    public function testPostUpdateActionGivenTokenIsInvalid()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordResetHasFailed(PasswordBroker::INVALID_TOKEN);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken', [])->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', trans(PasswordBroker::INVALID_TOKEN))->andReturnNull();

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }

    /**
     * Test POST /admin/forgot/reset given invalid user.
     *
     * @test
     */
    public function testPostUpdateActionGivenInvalidUser()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordBrokerController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordResetHasFailed(PasswordBroker::INVALID_USER);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::forgot/reset/auniquetoken', [])->andReturn('reset');
        Messages::shouldReceive('add')->once()->with('error', trans(PasswordBroker::INVALID_USER))->andReturnNull();

        $this->call('POST', 'admin/forgot/reset', $input);
        $this->assertRedirectedTo('reset');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\Account\PasswordBroker
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\Account\PasswordBroker');

        $this->app->instance('Orchestra\Foundation\Processor\Account\PasswordBroker', $processor);

        return $processor;
    }

    /**
     * Get sample input.
     *
     * @return array
     */
    protected function getInput()
    {
        return [
            'email' => 'email@orchestraplatform.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => 'auniquetoken',
        ];
    }
}
