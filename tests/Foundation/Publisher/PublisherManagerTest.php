<?php

namespace Orchestra\Foundation\TestCase\Publisher;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Publisher\PublisherManager;

class PublisherManagerTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    private $app;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $this->app = new Container();

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::getDefaultDriver()
     * method.
     *
     * @test
     */
    public function testGetDefaultDriverMethod()
    {
        $app = $this->app;

        $app['session'] = $session = m::mock('\Illuminate\Session\SessionInterface');
        $app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp\Client');
        $app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $app['orchestra.publisher.ftp'] = m::mock('\Orchestra\Contracts\Publisher\Uploader');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp');

        $stub = (new PublisherManager($app))->attach($memory);
        $ftp = $stub->driver();

        $this->assertInstanceOf('\Orchestra\Contracts\Publisher\Uploader', $ftp);
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::execute() method.
     *
     * @test
     */
    public function testExecuteMethod()
    {
        $app = $this->app;

        $app['orchestra.messages'] = $messages = m::mock('\Orchestra\Contracts\Messages\MessageBag');
        $app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Contracts\Publisher\Uploader');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])->andReturn(['a', 'b'])
            ->shouldReceive('get')->times(2)->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp')
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', ['b'])->andReturnNull();
        $messages->shouldReceive('add')->once()->with('success', m::any())->andReturnNull()
            ->shouldReceive('add')->once()->with('error', m::any())->andReturnNull();
        $translator->shouldReceive('trans')->andReturn('foo');
        $client->shouldReceive('upload')->with('a')->andReturnTrue()
            ->shouldReceive('upload')->with('b')->andThrow('\Exception');

        $stub = (new PublisherManager($app))->attach($memory);

        $this->assertTrue($stub->execute());
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::queue() method.
     *
     * @test
     */
    public function testQueueMethod()
    {
        $app = $this->app;
        $app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])
                ->andReturn(['foo', 'foobar'])
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', m::any())
                ->andReturnNull();

        $stub = (new PublisherManager($app))->attach($memory);
        $this->assertTrue($stub->queue(['foo', 'bar']));
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::queued() method.
     *
     * @test
     */
    public function testQueuedMethod()
    {
        $app = $this->app;
        $app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])->andReturn('foo');

        $stub = (new PublisherManager($app))->attach($memory);
        $this->assertEquals('foo', $stub->queued());
    }
}
