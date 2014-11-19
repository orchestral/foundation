<?php namespace Orchestra\Foundation\Routing\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Testing\TestCase;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Publisher;

class PublisherControllerTest extends TestCase
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
     * Test GET /admin/publisher
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $this->getProcessorMock()->shouldReceive('executeAndRedirect')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\PublisherController'))
            ->andReturnUsing(function ($listener) {
                return $listener->redirectToCurrentPublisher();
            });

        $this->call('GET', 'admin/publisher');
        $this->assertRedirectedTo(handles('orchestra::publisher/ftp'));
    }

    /**
     * Test GET /admin/publisher/ftp
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
     * Test POST /admin/publisher/ftp
     *
     * @test
     */
    public function testPostFtpAction()
    {
        $input = $this->getInput();
        $input['connection-type'] = 'ftp';

        $this->getProcessorMock()->shouldReceive('publish')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\PublisherController'), m::type('Array'))
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
            ->with(m::type('\Orchestra\Foundation\Routing\PublisherController'), m::type('Array'))
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
            m::mock('\Illuminate\Session\Store')
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
            'host'     => 'localhost',
            'username' => 'foo',
            'password' => 'foobar',
        ];
    }
}
