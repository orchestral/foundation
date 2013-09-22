<?php namespace Orchestra\Foundation\TestCase;

use Mockery as m;
use Orchestra\Foundation\Mail;
use Illuminate\Support\SerializableClosure;

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
			'orchestra.memory' => ($memory = m::mock('Memory')),
			'mailer' => $mailer = m::mock('Mailer'),
		);

		$memory->shouldReceive('makeOrFallback')->once()->andReturn($memory)
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
			'orchestra.memory' => $memory = m::mock('Memory'),
			'queue' => $queue = m::mock('QueueListener'),
		);

		$with = array(
			'view'     => 'foo.bar',
			'data'     => array('foo' => 'foobar'),
			'callback' => function () { },
		);

		$memory->shouldReceive('makeOrFallback')->once()->andReturn($memory)
			->shouldReceive('get')->once()->with('email.queue', false)->andReturn(true);
		$queue->shouldReceive('push')->once()
			->with('orchestra.mail@handleQueuedMessage', m::type('Array'), m::any())->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->push($with['view'], $with['data'], $with['callback']));
	}

	/**
	 * Test Orchestra\Foundation\Mail::send() method.
	 *
	 * @test
	 */
	public function testSendMethod()
	{
		$app = array(
			'mailer' => $mailer = m::mock('Mailer'),
		);

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
			'queue' => $queue = m::mock('QueueListener'),
		);

		$with = array(
			'view'     => 'foo.bar',
			'data'     => array('foo' => 'foobar'),
			'callback' => function () { },
		);

		$queue->shouldReceive('push')->once()
			->with('orchestra.mail@handleQueuedMessage', m::type('Array'), m::any())->andReturn(true);

		$stub = new Mail($app);
		$this->assertTrue($stub->queue($with['view'], $with['data'], $with['callback']));
	}

	/**
	 * Data provider
	 * 
	 * @return array
	 */
	public function queueMessageDataProvdier()
    {
    	$closure = function () {};
		$callback = new SerializableClosure($closure);

    	return array(
    		array(
				'view'     => 'foo.bar',
				'data'     => array('foo' => 'foobar'),
				'callback' => serialize($callback),
			),
			array(
				'view'     => 'foo.bar',
				'data'     => array('foo' => 'foobar'),
				'callback' => "hello world",
			)
    	);
    }

	/**
	 * Test Orchestra\Foundation\Mail::handleQueuedMessage() method.
	 *
	 * @test
	 * @dataProvider queueMessageDataProvdier
	 */
	public function testHandleQueuedMessageMethod($view, $data, $callback)
	{
		$app = array('mailer' => $mailer = m::mock('Mailer'));
		$job  = m::mock('Job');
		
		$job->shouldReceive('delete')->once()->andReturn(null);
		$mailer->shouldReceive('send')->once()
			->with($view, $data, m::any())->andReturn(true);

		$stub = new Mail($app);
		$stub->handleQueuedMessage($job, compact('view', 'data', 'callback'));
	}
}
