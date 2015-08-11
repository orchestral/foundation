<?php namespace Orchestra\Foundation\Http\Controllers\Account\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;

class ProfileUpdaterControllerTest extends TestCase
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
     * Test GET /admin/account.
     *
     * @test
     */
    public function testGetEditAction()
    {
        $this->getProcessorMock()->shouldReceive('edit')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\ProfileUpdaterController'))
            ->andReturnUsing(function ($listener) {
                return $listener->showProfileChanger([]);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::account.index', [], [])->andReturn('show.profile.changer');

        $this->call('GET', 'admin/account');
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
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\ProfileUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->profileUpdated([]);
            });

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::account', [])->andReturn('account');

        $this->call('POST', 'admin/account', $input);
        $this->assertRedirectedTo('account');
    }

    /**
     * Test POST /admin/account with invalid user id.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testPostIndexActionGivenInvalidUserId()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\ProfileUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->abortWhenUserMismatched();
            });

        $this->call('POST', 'admin/account', $input);
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
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\ProfileUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updateProfileFailed([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account', [])->andReturn('account');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

        $this->call('POST', 'admin/account', $input);
        $this->assertRedirectedTo('account');
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
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\Account\ProfileUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updateProfileFailedValidation([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account', [])->andReturn('account');

        $this->call('POST', 'admin/account', $input);
        $this->assertRedirectedTo('account');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\Account\ProfileUpdater
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\Account\ProfileUpdater');

        $this->app->instance('Orchestra\Foundation\Processor\Account\ProfileUpdater', $processor);

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
            'email' => 'email@orchestraplatform.com',
            'fullname' => 'Administrator',
        ];
    }
}
