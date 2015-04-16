<?php namespace Orchestra\Foundation\Processor\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\Processor\AssetPublisher;

class AssetPublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Processor\AssetPublisher::executeAndRedirect()
     * method.
     *
     * @test
     */
    public function testExecuteAndRedirectMethod()
    {
        $listener  = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $session   = m::mock('\Illuminate\Session\Store');

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('connected')->once()->andReturn(true)
            ->shouldReceive('execute')->once()->andReturn(true);
        $listener->shouldReceive('redirectToCurrentPublisher')->once()->andReturn('redirected');

        $this->assertEquals('redirected', $stub->executeAndRedirect($listener));
    }

    /**
     * Test Orchestra\Foundation\Processor\AssetPublisher::publish()
     * method.
     *
     * @test
     */
    public function testPublishMethod()
    {
        $listener  = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $session   = m::mock('\Illuminate\Session\Store');

        $input = $this->getInput();

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('queued')->once()->andReturn(['laravel/framework'])
            ->shouldReceive('connect')->once()->andReturn(true)
            ->shouldReceive('connected')->once()->andReturn(true)
            ->shouldReceive('execute')->once()->andReturn(true);
        $session->shouldReceive('put')->once()->with('orchestra.ftp', $input)->andReturnNull();
        $listener->shouldReceive('publishingHasSucceed')->once()->andReturn('asset.published');

        $this->assertEquals('asset.published', $stub->publish($listener, $input));
    }

    /**
     * Test Orchestra\Foundation\Processor\AssetPublisher::publish()
     * method when connection failed.
     *
     * @test
     */
    public function testPublishMethodGivenConnectionFailed()
    {
        $listener  = m::mock('\Orchestra\Contracts\Foundation\Listener\AssetPublishing');
        $publisher = m::mock('\Orchestra\Foundation\Publisher\PublisherManager');
        $uploader  = m::mock('\Orchestra\Contracts\Publisher\Uploader');
        $session   = m::mock('\Illuminate\Session\Store');

        $input = $this->getInput();

        $stub = new AssetPublisher($publisher, $session);

        $publisher->shouldReceive('queued')->once()->andReturn(['laravel/framework'])
            ->shouldReceive('connect')->once()->andThrow('\Orchestra\Contracts\Publisher\ServerException');
        $session->shouldReceive('forget')->once()->with('orchestra.ftp')->andReturnNull();
        $listener->shouldReceive('publishingHasFailed')->once()->andReturn(['error' => 'failed']);

        $this->assertEquals(['error' => 'failed'], $stub->publish($listener, $input));
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
            'ssl'      => false,
        ];
    }
}
