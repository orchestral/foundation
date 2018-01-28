<?php

namespace Orchestra\Tests\Unit\Http\Controllers;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Testing\BrowserKit\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PublisherControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableMiddlewareForAllTests();
    }

    /**
     * Test GET /admin/publisher.
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $this->getProcessorMock()->shouldReceive('executeAndRedirect')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\PublisherController'))
            ->andReturnUsing(function ($listener) {
                return $listener->redirectToCurrentPublisher();
            });

        $this->call('GET', 'admin/publisher');
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Test GET /admin/publisher/ftp.
     *
     * @test
     */
    public function testGetFtpAction()
    {
        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::publisher.ftp', [], [])->andReturn('get.ftp');

        $this->call('GET', 'admin/publisher/ftp');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/publisher/ftp.
     *
     * @test
     */
    public function testPostFtpAction()
    {
        $input = $this->getInput();
        $input['connection-type'] = 'ftp';

        $this->getProcessorMock()->shouldReceive('publish')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\PublisherController'), m::type('Array'))
            ->andReturnUsing(function ($listener) {
                return $listener->publishingHasSucceed();
            });

        $this->call('POST', 'admin/publisher/ftp', $input);
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Test POST /admin/publisher/ftp when FTP connect failed.
     *
     * @test
     */
    public function testPostFtpActionWhenFtpConnectFailed()
    {
        $input = $this->getInput();
        $input['connection-type'] = 'ftp';

        $this->getProcessorMock()->shouldReceive('publish')->once()
            ->with(m::type('\Orchestra\Foundation\Http\Controllers\PublisherController'), m::type('Array'))
            ->andReturnUsing(function ($listener) {
                return $listener->publishingHasFailed(['error' => 'failed']);
            });

        $this->call('POST', 'admin/publisher/ftp', $input);
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\AssetPublisher
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\AssetPublisher', [
            m::mock('\Orchestra\Foundation\Publisher\PublisherManager'),
            m::mock('\Illuminate\Session\Store'),
        ]);

        $this->app->instance('Orchestra\Foundation\Processor\AssetPublisher', $processor);

        return $processor;
    }

    /**
     * Get request input.
     *
     * @return array
     */
    protected function getInput()
    {
        return [
            'host' => 'localhost',
            'username' => 'foo',
            'password' => 'foobar',
        ];
    }
}
