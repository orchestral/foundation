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
	 * @return \Illuminate\Mail\Mailer
	 */
	public function send($view, array $data, $callback)
	{
		$method = 'queue';
		$memory = $this->app['orchestra.memory']->make();

		// Push configuration from database to runtime configuration.
		$this->app['config']->set('mail', $memory->get('email'));

		if (false === $memory->get('email.queue', false)) $method = 'send';

		return $this->push($method, $view, $data, $callback);
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
	protected function push($method, $view, $data, $callback)
	{
		return call_user_func(array($this->app['mailer'], $method), $view, $data, $callback);
	}
}
