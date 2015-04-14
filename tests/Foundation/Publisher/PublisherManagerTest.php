<?php namespace Orchestra\Foundation\Publisher\TestCase;

use Mockery as m;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Orchestra\Foundation\Publisher\PublisherManager;

class PublisherManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    private $app = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Container();

        Facade::clearResolvedInstances();
        Container::setInstance($this->app);
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
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

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp');
        $session->shouldReceive('get')->once()->with('orchestra.ftp', [])->andReturn(['foo']);
        $client->shouldReceive('setUp')->once()->with(['foo'])->andReturnNull()
            ->shouldReceive('connect')->once()->andReturn(true);

        $stub = new PublisherManager($app);
        $ftp  = $stub->driver();

        $this->assertInstanceOf('\Orchestra\Foundation\Publisher\Ftp', $ftp);
        $this->assertInstanceOf('\Orchestra\Support\Ftp\Client', $ftp->getConnection());
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::execute() method.
     *
     * @test
     */
    public function testExecuteMethod()
    {
        $app = $this->app;

        $app['session'] = $session = m::mock('\Illuminate\Session\SessionInterface');
        $app['orchestra.messages'] = $messages = m::mock('\Orchestra\Contracts\Messages\MessageBag');
        $app['path.public'] = $path = '/var/foo/public';
        $app['files'] = $file = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp\Client');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator')->makePartial();
        $app['orchestra.platform.memory'] = $memory = m::mock('\Orchestra\Contracts\Memory\Provider');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', [])->andReturn(['a', 'b'])
            ->shouldReceive('get')->times(2)->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp')
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', ['b'])->andReturnNull();
        $session->shouldReceive('get')->once()->with('orchestra.ftp', [])->andReturn(['manager-foo']);
        $messages->shouldReceive('add')->once()->with('success', m::any())->andReturnNull()
            ->shouldReceive('add')->once()->with('error', m::any())->andReturnNull();
        $translator->shouldReceive('trans')->andReturn('foo');
        $client->shouldReceive('setUp')->once()->with(['manager-foo'])->andReturnNull()
            ->shouldReceive('connect')->once()->andReturn(true)
            ->shouldReceive('permission')->with($path.'/packages/', 0777)->andReturn(true)
            ->shouldReceive('permission')->with($path.'/packages/', 0755)->andReturn(true)
            ->shouldReceive('makeDirectory')->with($path.'/packages/a/')->andReturn(true)
            ->shouldReceive('makeDirectory')->with($path.'/packages/b/')->andReturn(true);
        $file->shouldReceive('isDirectory')->once()->with($path.'/packages/a/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with($path.'/packages/b/')->andReturn(false);
        $extension->shouldReceive('activate')->once()->with('a')->andReturnNull()
            ->shouldReceive('activate')->once()->with('b')->andThrow('\Exception');

        $stub = new PublisherManager($app);

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

        $stub = new PublisherManager($app);
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

        $stub = new PublisherManager($app);
        $this->assertEquals('foo', $stub->queued());
    }
}
