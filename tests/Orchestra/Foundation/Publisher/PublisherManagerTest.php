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
        $this->app = new Container;

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
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
     * Get Memory mock.
     *
     * @private
     * @return Mockery
     */
    private function getMemoryMock()
    {
        $memory = m::mock('\Orchestra\Memory\MemoryManager[make]');

        $memory->shouldReceive('make')->once()->andReturn($memory);

        return $memory;
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

        $app['session'] = $session = m::mock('\Illuminate\Session\Store[get]');
        $app['orchestra.memory'] = $memory = $this->getMemoryMock();
        $app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp');

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp');
        $session->shouldReceive('get')->once()->with('orchestra.ftp', array())->andReturn(array('foo'));
        $client->shouldReceive('setUp')->once()->with(array('foo'))->andReturnNull()
            ->shouldReceive('connect')->once()->andReturn(true);

        $stub = new PublisherManager($app);
        $ftp  = $stub->driver();

        $this->assertInstanceOf('\Orchestra\Foundation\Publisher\Ftp', $ftp);
        $this->assertInstanceOf('\Orchestra\Support\Ftp', $ftp->getConnection());
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::execute() method.
     *
     * @test
     */
    public function testExecuteMethod()
    {
        $app = $this->app;

        $app['session'] = $session = m::mock('\Illuminate\Session\Store[get]');
        $app['orchestra.memory'] = $memory = m::mock('\Orchestra\Memory\MemoryManager[make]');
        $app['orchestra.messages'] = $messages = m::mock('\Orchestra\Support\Messages[add]');
        $app['path.public'] = $path = '/var/foo/public';
        $app['files'] = $file = m::mock('\Illuminate\Filesystem\Filesystem[isDirectory]');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Environment[activate]');
        $app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp');
        $app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator[trans]');

        $memoryProvider = m::mock('\Orchestra\Memory\Provider[get,put]');

        $memory->shouldReceive('make')->times(4)->andReturn($memoryProvider);

        $memoryProvider->shouldReceive('get')->once()->with('orchestra.publisher.queue', array())->andReturn(array('a', 'b'))
            ->shouldReceive('get')->times(2)->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp')
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', array('b'))->andReturnNull();
        $session->shouldReceive('get')->once()->with('orchestra.ftp', array())->andReturn(array('manager-foo'));
        $messages->shouldReceive('add')->once()->with('success', m::any())->andReturnNull()
            ->shouldReceive('add')->once()->with('error', m::any())->andReturnNull();
        $translator->shouldReceive('trans')->andReturn('foo');
        $client->shouldReceive('setUp')->once()->with(array('manager-foo'))->andReturnNull()
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
        $app['orchestra.memory'] = $memory = m::mock('Memory');

        $memory->shouldReceive('make')->twice()->andReturn($memory)
            ->shouldReceive('get')->once()->with('orchestra.publisher.queue', array())->andReturn(array('foo', 'foobar'))
            ->shouldReceive('put')->once()->with('orchestra.publisher.queue', m::any())->andReturnNull();

        $stub = new PublisherManager($app);
        $this->assertTrue($stub->queue(array('foo', 'bar')));
    }

    /**
     * Test Orchestra\Foundation\Publisher\PublisherManager::queued() method.
     *
     * @test
     */
    public function testQueuedMethod()
    {
        $app = $this->app;
        $app['orchestra.memory'] = $memory = $this->getMemoryMock();

        $memory->shouldReceive('get')->once()->with('orchestra.publisher.queue', array())->andReturn('foo');

        $stub = new PublisherManager($app);
        $this->assertEquals('foo', $stub->queued());
    }
}
