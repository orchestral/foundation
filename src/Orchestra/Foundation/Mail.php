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
		$this->app    = $app;
		$this->memory = $this->app['orchestra.memory']->make();
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
	public function push($view, array $data, $callback)
	{
		$method = 'queue';

		if (false === $this->memory->get('email.queue', false)) $method = 'send';

		return $this->dispatchMailer($method, $view, $data, $callback);
	}

	/**
	 * Force Orchestra Platform to send email directly.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	public function send($view, array $data, $callback)
	{		
		return $this->dispatchMailer('send', $view, $data, $callback);
	}

	/**
	 * Force Orchestra Platform to send email using queue.
	 *
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return \Illuminate\Mail\Mailer
	 */
	public function queue($view, array $data, $callback)
	{		
		return $this->dispatchMailer('queue', $view, $data, $callback);
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
	protected function dispatchMailer($method, $view, $data, $callback)
	{
		return call_user_func(array($this->app['mailer'], $method), $view, $data, $callback);
	}
}
