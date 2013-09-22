<?php namespace Orchestra\Foundation;

use Closure;
use Illuminate\Support\SerializableClosure;
use Illuminate\Support\Facades\Mail as M;

class Mail {

	/**
	 * Application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Construct a new Mail instance.
	 *
	 * @param  \Illuminate\Foundation\Application   $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}
	
	/**
	 * Allow Orchestra Platform to either use send or queue based on 
	 * settings.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @param  string           $queue
	 * @return \Illuminate\Mail\Mailer
	 */
	public function push($view, array $data, $callback, $queue = null)
	{
		$method = 'queue';
		$memory = $this->app['orchestra.memory']->makeOrFallback();

		if (false === $memory->get('email.queue', false)) $method = 'send';

		return call_user_func(array($this, $method), $view, $data, $callback, $queue);
	}

	/**
	 * Force Orchestra Platform to send email directly.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @param  string           $queue
	 * @return \Illuminate\Mail\Mailer
	 */
	public function send($view, array $data, $callback)
	{		
		return $this->app['mailer']->send($view, $data, $callback);
	}

	/**
	 * Force Orchestra Platform to send email using queue.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @param  string           $queue
	 * @return \Illuminate\Mail\Mailer
	 */
	public function queue($view, array $data, $callback, $queue = null)
	{
		$callback = $this->buildQueueCallable($callback);
		$with     = compact('view', 'data', 'callback');

		return $this->app['queue']->push('orchestra.mail@handleQueuedMessage', $with, $queue);
	}

	/**
	 * Build the callable for a queued e-mail job.
	 *
	 * @param  mixed  $callback
	 * @return mixed
	 */
	protected function buildQueueCallable($callback)
	{
		if ( ! $callback instanceof Closure) return $callback;

		return serialize(new SerializableClosure($callback));
	}

	/**
	 * Handle a queued e-mail message job.
	 *
	 * @param  \Illuminate\Queue\Jobs\Job  $job
	 * @param  array  $data
	 * @return void
	 */
	public function handleQueuedMessage($job, $data)
	{
		$this->send($data['view'], $data['data'], $this->getQueuedCallable($data));

		$job->delete();
	}

	/**
	 * Get the true callable for a queued e-mail message.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	protected function getQueuedCallable(array $data)
	{
		if (str_contains($data['callback'], 'SerializableClosure'))
		{
			return with(unserialize($data['callback']))->getClosure();
		}

		return $data['callback'];
	}
}
