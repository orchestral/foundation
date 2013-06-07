<?php namespace Orchestra\Foundation\Tests\Publisher;

use Mockery as m;
use Orchestra\Foundation\Publisher\Ftp;

class FtpTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app = null;

	/**
	 * FTP Client instance.
	 *
	 * @var Orchestra\Support\Ftp
	 */
	private $client = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app    = new \Illuminate\Container\Container;
		$this->client = m::mock('\Orchestra\Support\Ftp');
	}
	
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->app);
		unset($this->client);
		m::close();
	}

	/**
	 * Get Session mock.
	 *
	 * @access private
	 * @return Mockery
	 */
	private function getSessionMock()
	{
		$session = m::mock('Session');

		$session->shouldReceive('get')->once()->with('orchestra.ftp', array())->andReturn(array('ftpconfig'));

		return $session;
	}

	/**
	 * Test constructing Orchestra\Foundation\Publisher\Ftp.
	 *
	 * @test
	 */
	public function testConstructMethod()
	{
		$app    = $this->app;
		$client = $this->client;

		$app['session'] = $this->getSessionMock();

		$client->shouldReceive('setUp')->once()->with(array('ftpconfig'))->andReturn(null)
			->shouldReceive('connect')->once()->andReturn(true)
			->shouldReceive('connected')->once()->andReturn(true);

		$stub = new Ftp($app, $client);

		$this->assertEquals($client, $stub->getConnection());
		$this->assertTrue($stub->connected());

		$this->assertEquals('/domains/foo.bar/public', $stub->basePath('/home/foo/domains/foo.bar/public'));
		$this->assertEquals('/var/html/foo.bar/public', $stub->basePath('/var/html/foo.bar/public'));
	}

	/**
	 * Test constructing Orchestra\Foundation\Publisher\Ftp throws ServerException.
	 *
	 * @test
	 */
	public function testConstructMethodThrowsServerException()
	{
		$app    = $this->app;
		$client = $this->client;

		$app['session'] = $session = $this->getSessionMock();

		$session->shouldReceive('put')->once()->with('orchestra.ftp', array())->andReturn(null);
		$client->shouldReceive('setUp')->once()->with(array('ftpconfig'))->andReturn(null)
			->shouldReceive('connect')->once()->andThrow('\Orchestra\Support\Ftp\ServerException');

		$stub = new Ftp($app, $client);
	}

	/**
	 * Test Orchestra\Foundation\Publisher\Ftp::upload() method.
	 *
	 * @test
	 */
	public function testUploadMethod()
	{
		$app    = $this->app;
		$client = $this->client;

		$app['session'] = $this->getSessionMock();
		$app['path.public'] = '/var/foo/public';
		$app['files'] = $file = m::mock('Filesystem');
		$app['orchestra.extension'] = $extension = m::mock('Extension');

		$client->shouldReceive('setUp')->once()->with(array('ftpconfig'))->andReturn(null)
			->shouldReceive('connect')->once()->andReturn(true)
			->shouldReceive('permission')->once()->with('/var/foo/public/packages/laravel/framework/', 0777)->andReturn(null)
			->shouldReceive('permission')->once()->with('/var/foo/public/packages/laravel/framework/', 0755)->andReturn(null)
			->shouldReceive('allFiles')->twice()->with('/var/foo/public/packages/laravel/framework/')->andReturn(array(
				'/..',
				'/.',
				'recursive-foobar',
			))
			->shouldReceive('permission')->once()->with('recursive-foobar', 0777)->andReturn(null)
			->shouldReceive('allFiles')->once()->with('recursive-foobar')->andThrow('\RuntimeException');
		$file->shouldReceive('isDirectory')->once()->with('/var/foo/public/packages/laravel/framework/')->andReturn(true);
		$extension->shouldReceive('activate')->once()->with('laravel/framework')->andReturn(null);

		$stub = new Ftp($app, $client);
		$stub->upload('laravel/framework');
	}
}
