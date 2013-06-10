<?php namespace Orchestra\Foundation\Tests\Routing;

use Mockery as m;
use Orchestra\Services\TestCase;

class PublisherControllerTest extends TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test GET /admin/publisher
	 * 
	 * @test
	 */
	public function testGetIndexAction()
	{
		\Orchestra\Support\Facades\Publisher::shouldReceive('connected')->once()->andReturn(true);
		\Orchestra\Support\Facades\Publisher::shouldReceive('execute')->once()->andReturn(true);

		$this->call('GET', 'admin/publisher');
		$this->assertRedirectedTo(handles('orchestra/foundation::publisher/ftp'));
	}

	/**
	 * Test GET /admin/publisher/ftp
	 * 
	 * @test
	 */
	public function testGetFtpAction()
	{
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
		$input = array(
			'host'     => 'localhost',
			'username' => 'foo',
			'password' => 'foobar',
		);

		\Orchestra\Support\Facades\Publisher::shouldReceive('connect')->once()->andReturn(true);
		\Orchestra\Support\Facades\Publisher::shouldReceive('queued')->once()
			->andReturn(array('laravel/framework'));
		\Orchestra\Support\Facades\Publisher::shouldReceive('connected')->once()->andReturn(true);
		\Orchestra\Support\Facades\Publisher::shouldReceive('execute')->once()->andReturn(true);
		
		$input['connection-type'] = 'ftp';

		$this->call('POST', 'admin/publisher/ftp', $input);
		$this->assertRedirectedTo(handles('orchestra/foundation::publisher/ftp'));
	}

	/**
	 * Test POST /admin/publisher/ftp when FTP connect failed.
	 * 
	 * @test
	 */
	public function testPostFtpActionWhenFtpConnectFailed()
	{
		$input = array(
			'host'     => 'localhost',
			'username' => 'foo',
			'password' => 'foobar',
		);

		\Orchestra\Support\Facades\Publisher::shouldReceive('connect')->once()->andThrow('\Orchestra\Support\FTP\ServerException');
		\Orchestra\Support\Facades\Publisher::shouldReceive('queued')->once()
			->andReturn(array('laravel/framework'));
		
		\Orchestra\Support\Facades\Messages::shouldReceive('add')->once()
			->with('error', m::any())->andReturn(true);
		
		$input['connection-type'] = 'ftp';

		$this->call('POST', 'admin/publisher/ftp', $input);
		$this->assertRedirectedTo(handles('orchestra/foundation::publisher/ftp'));
	}
}
