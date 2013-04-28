<?php namespace Orchestra\Foundation\Publisher;

use Illuminate\Support\Manager;
use Orchestra\Support\Ftp as FtpClient;

class PublisherManager extends Manager {

	/**
	 * Create an instance of the Ftp driver.
	 *
	 * @return Orchestra\Foundation\Publisher\Ftp
	 */
	public function createFtpDriver()
	{
		return new Ftp($this->app, new FtpClient);
	}

	/**
	 * Get the default authentication driver name.
	 *
	 * @return string
	 */
	protected function getDefaultDriver()
	{
		$memory = $this->app['orchestra.memory']->make();

		return $memory->get('orchestra.publisher.driver', 'ftp');
	}

	/**
	 * Add a process to be queue.
	 *
	 * @access public
	 * @param  string   $queue
	 * @return bool
	 */
	public function queue($queue)
	{
		$this->app['orchestra.memory']->make();
		$queue = $this->queued() + (array) $queue;
		$memory->put('site.publisher.queue', $queue);

		return true;
	}

	/**
	 * Get a current queue.
	 *
	 * @access public
	 * @return array
	 */
	public function queued()
	{
		$memory = $this->app['orchestra.memory']->make();
		return $memory->get('orchestra.publisher.queue', array());
	}
}
