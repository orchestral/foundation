<?php

namespace Orchestra\Tests\Unit\Http\Controllers\Account;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PasswordUpdaterControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Test GET /admin/account.
     *
     * @test
     */
    public function testGetEditAction()
    {
        $this->getProcessorMock()->shouldReceive('edit')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'))
            ->andReturnUsing(function ($listener) {
                return $listener->showPasswordChanger([]);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::account.password', [], [])->andReturn('show.password.changer');

        $this->call('GET', 'admin/account/password');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/account.
     *
     * @test
     */
    public function testPostUpdateAction()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordUpdated([]);
            });

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with invalid user id.
     *
     * @test
     */
    public function testPostIndexActionGivenInvalidUserId()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->abortWhenUserMismatched();
            });

        $this->call('POST', 'admin/account/password', $input);
        $this->assertResponseStatus(500);
    }

    /**
     * Test POST /admin/account with database error.
     *
     * @test
     */
    public function testPostIndexActionGivenDatabaseError()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updatePasswordFailed([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with validation failed.
     *
     * @test
     */
    public function testPostIndexActionGivenValidationFailed()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updatePasswordFailedValidation([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with hash check failed.
     *
     * @test
     */
    public function testPostIndexActionGivenHashMissmatch()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->verifyCurrentPasswordFailed([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processors\Account\PasswordUpdater
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processors\Account\PasswordUpdater');

        $this->app->instance('Orchestra\Foundation\Processors\Account\PasswordUpdater', $processor);

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
            'id' => '1',
            'current_password' => '123456',
            'new_password' => 'qwerty',
        ];
    }
}
