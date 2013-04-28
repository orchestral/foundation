<?php namespace Orchestra\Foundation\Publisher;

use Exception;
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

	/**
	 * Execute the queue.
	 * 
	 * @access public
	 * @return void
	 */
	public function execute()
	{
		$memory   = $this->app['orchestra.memory'];
		$messages = $this->app['orchestra.messages'];
		$queues   = $this->queued();

		foreach ($queues as $key => $queue)
		{
			try
			{
				$this->driver()->upload($queue);
				
				$messages->add('success', trans('orchestra/foundation::response.extensions.activate', array(
					'name' => $queue,
				)));

				unset($queues[$key]);
			}
			catch (Exception $e)
			{
				// this could be anything.
				$messages->add('error', $e->getMessage());
			}
		}

		$memory->get('orchestra.publisher.queue', $queues);

		return $msg;
	}
}
