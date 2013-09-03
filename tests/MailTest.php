<?php namespace Orchestra\Foundation\Tests;

use Mockery as m;
use Orchestra\Foundation\Mail;

class MailTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Foundation\Mail::push() method uses Mail::send().
	 *
	 * @test
	 */
	public function testPushMethodUsesSend()
	{
		$app = array(
			'config' => $config = m::mock('Config\Manager'),
			'orchestra.memory' => ($memory = m::mock('Memory')),
			'mailer' => $mailer = m::mock('Mailer'),
		);

		$config->shouldReceive('set')->once()->with('mail', 'email-config')->andReturn(null);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('get')->once()->with('email')->andReturn('email-config')
			->shouldReceive('get')->once()->with('email.queue', false)->andReturn(false);
		$mailer->shouldReceive('send')->once()->with('foo.bar', array('foo' => 'foobar'), '')->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->push('foo.bar', array('foo' => 'foobar'), ''));
	}

	/**
	 * Test Orchestra\Foundation\Mail::push() method uses Mail::queue().
	 *
	 * @test
	 */
	public function testPushMethodUsesQueue()
	{
		$app = array(
			'config' => $config = m::mock('Config\Manager'),
			'orchestra.memory' => $memory = m::mock('Memory'),
			'mailer' => $mailer = m::mock('Mailer'),
		);

		$config->shouldReceive('set')->once()->with('mail', 'email-config')->andReturn(null);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('get')->once()->with('email')->andReturn('email-config')
			->shouldReceive('get')->once()->with('email.queue', false)->andReturn(true);
		$mailer->shouldReceive('queue')->once()->with('foo.bar', array('foo' => 'foobar'), '')->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->push('foo.bar', array('foo' => 'foobar'), ''));
	}

	/**
	 * Test Orchestra\Foundation\Mail::send() method.
	 *
	 * @test
	 */
	public function testSendMethod()
	{
		$app = array(
			'config' => $config = m::mock('Config\Manager'),
			'orchestra.memory' => ($memory = m::mock('Memory')),
			'mailer' => $mailer = m::mock('Mailer'),
		);

		$config->shouldReceive('set')->once()->with('mail', 'email-config')->andReturn(null);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('get')->once()->with('email')->andReturn('email-config');
		$mailer->shouldReceive('send')->once()->with('foo.bar', array('foo' => 'foobar'), '')->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->send('foo.bar', array('foo' => 'foobar'), ''));
	}

	/**
	 * Test Orchestra\Foundation\Mail::queue() method.
	 *
	 * @test
	 */
	public function testQueueMethod()
	{
		$app = array(
			'config' => $config = m::mock('Config\Manager'),
			'orchestra.memory' => ($memory = m::mock('Memory')),
			'mailer' => $mailer = m::mock('Mailer'),
		);

		$config->shouldReceive('set')->once()->with('mail', 'email-config')->andReturn(null);
		$memory->shouldReceive('make')->once()->andReturn($memory)
			->shouldReceive('get')->once()->with('email')->andReturn('email-config');
		$mailer->shouldReceive('queue')->once()->with('foo.bar', array('foo' => 'foobar'), '')->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->queue('foo.bar', array('foo' => 'foobar'), ''));
	}
}
