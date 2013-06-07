<?php namespace Orchestra\Foundation\Tests\Publisher;

use Mockery as m;
use Orchestra\Foundation\Publisher\PublisherManager;

class PublisherManagerTest extends \PHPUnit_Framework_TestCase {

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
		$this->app = new \Illuminate\Container\Container;
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
		$memory = m::mock('Memory');

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

		$app['session'] = $session = m::mock('Session');
		$app['orchestra.memory'] = $memory = $this->getMemoryMock();
		$app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp');

		$memory->shouldReceive('get')->once()->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp');
		$session->shouldReceive('get')->once()->with('orchestra.ftp', array())->andReturn(array('foo'));
		$client->shouldReceive('setUp')->once()->with(array('foo'))->andReturn(null)
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
		$app    = $this->app;

		$app['session'] = $session = m::mock('Session');
		$app['orchestra.memory'] = $memory = m::mock('Memory');
		$app['orchestra.messages'] = $messages = m::mock('Messages');
		$app['path.public'] = '/var/foo/public';
		$app['files'] = $file = m::mock('Filesystem');
		$app['orchestra.extension'] = $extension = m::mock('Extension');
		$app['orchestra.publisher.ftp'] = $client = m::mock('\Orchestra\Support\Ftp');

		$memory->shouldReceive('make')->times(3)->andReturn($memory)
			->shouldReceive('get')->once()->with('orchestra.publisher.queue', array())->andReturn(array('a', 'b'))
			->shouldReceive('get')->times(2)->with('orchestra.publisher.driver', 'ftp')->andReturn('ftp')
			->shouldReceive('put')->once()->with('orchestra.publisher.queue', array('b'))->andReturn(null);
		$session->shouldReceive('get')->once()->with('orchestra.ftp', array())->andReturn(array('manager-foo'));
		$messages->shouldReceive('add')->once()->with('success', m::any())->andReturn(null)
			->shouldReceive('add')->once()->with('error', m::any())->andReturn(null);

		$client->shouldReceive('setUp')->once()->with(array('manager-foo'))->andReturn(null)
			->shouldReceive('connect')->once()->andReturn(true)
			->shouldReceive('permission')->with('/var/foo/public/packages/', 0777)->andReturn(true)
			->shouldReceive('permission')->with('/var/foo/public/packages/', 0755)->andReturn(true);
		$file->shouldReceive('isDirectory')->once()->with('/var/foo/public/packages/a/')->andReturn(false)
			->shouldReceive('isDirectory')->once()->with('/var/foo/public/packages/b/')->andReturn(false);
		$extension->shouldReceive('activate')->once()->with('a')->andReturn(null)
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
			->shouldReceive('put')->once()->with('orchestra.publisher.queue', m::any())->andReturn(null);

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
