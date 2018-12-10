<?php

namespace Orchestra\Tests\Unit\Processor;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Foundation\Processor\AssetPublisher;

class AssetPublisherTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_publish_and_redirect_user()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $session = m::mock('\Illuminate\Session\Store');

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('connected')->once()->andReturn(true)
            ->shouldReceive('execute')->once()->andReturn(true);
        $listener->shouldReceive('publishingHasSucceed')->once()->andReturn('redirected');

        $this->assertEquals('redirected', $stub->executeAndRedirect($listener));
    }

    /** @test */
    public function it_can_publish_for_package()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $session = m::mock('\Illuminate\Session\Store');

        $data = $this->getServerConnection();

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('queued')->once()->andReturn(['laravel/framework'])
            ->shouldReceive('connect')->once()->andReturn(true)
            ->shouldReceive('connected')->once()->andReturn(true)
            ->shouldReceive('execute')->once()->andReturn(true);
        $session->shouldReceive('put')->once()->with('orchestra.ftp', $data)->andReturnNull();
        $listener->shouldReceive('publishingHasSucceed')->once()->andReturn('asset.published');

        $this->assertEquals('asset.published', $stub->publish($listener, $data));
    }

    /** @test */
    public function it_cant_publish_given_connection_error()
    {
        $listener = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $uploader = m::mock('\Orchestra\Contracts\Publisher\Uploader');
        $session = m::mock('\Illuminate\Session\Store');

        $data = $this->getServerConnection();

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('queued')->once()->andReturn(['laravel/framework'])
            ->shouldReceive('connect')->once()->andThrow('\Orchestra\Contracts\Publisher\ServerException');
        $session->shouldReceive('forget')->once()->with('orchestra.ftp')->andReturnNull();
        $listener->shouldReceive('publishingHasFailed')->once()->andReturn(['error' => 'failed']);

        $this->assertEquals(['error' => 'failed'], $stub->publish($listener, $data));
    }

    /**
     * Get request input.
     *
     * @return array
     */
    protected function getServerConnection(): array
    {
        return [
            'host' => 'localhost',
            'username' => 'foo',
            'password' => 'foobar',
            'ssl' => false,
        ];
    }
}
