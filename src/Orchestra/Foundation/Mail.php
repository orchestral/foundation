<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\Mail as M;

class Mail {

	/**
	 * Application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Memory instance.
	 *
	 * @var \Orchestra\Memory\Drivers\Driver
	 */
	protected $memory = null;

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
	 * Load Memory Provider.
	 *
	 * @return void
	 */
	protected function loadMemoryProvider()
	{
		if ( ! is_null($this->memory)) return ;

		$this->memory = $this->app['orchestra.memory']->make();

		// Push configuration from database to runtime configuration.
		$this->app['config']->set('mail', $this->memory->get('email'));
	}
	
	/**
	 * Allow Orchestra Platform to either use send or queue based on 
	 * settings.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	public function send($view, array $data, $callback)
	{
		$this->loadMemoryProvider();

		$method = 'queue';

		if (false === $this->memory->get('email.queue', false)) $method = 'send';

		return $this->sendUsingMailer($method, $view, $data, $callback);
	}

	/**
	 * Force Orchestra Platform to send email directly.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	public function forceSend($view, array $data, $callback)
	{
		$this->loadMemoryProvider();
		
		return $this->sendUsingMailer('send', $view, $data, $callback);
	}

	/**
	 * Force Orchestra Platform to send email using queue.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	public function forceQueue($view, array $data, $callback)
	{
		$this->loadMemoryProvider();
		
		return $this->sendUsingMailer('queue', $view, $data, $callback);
	}

	/**
	 * Execute mail using selected method.
	 * 
	 * @param  string           $method
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	protected function sendUsingMailer($method, $view, $data, $callback)
	{
		return call_user_func(array($this->app['mailer'], $method), $view, $data, $callback);
	}
}
