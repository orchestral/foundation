<?php namespace Orchestra\Foundation;

use Closure;
use InvalidArgumentException;
use Illuminate\Support\SerializableClosure;
use Illuminate\Support\Facades\Mail as M;
use Swift_Mailer;
use Swift_SmtpTransport as SmtpTransport;
use Swift_MailTransport as MailTransport;
use Swift_SendmailTransport as SendmailTransport;

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
	 * The Swift Mailer instance.
	 *
	 * @var Swift_Mailer
	 */
	protected $swift;

	/**
	 * Construct a new Mail instance.
	 *
	 * @param  \Illuminate\Foundation\Application   $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app    = $app;
		$this->memory = $app['orchestra.memory']->makeOrFallback();
	}

	/**
	 * Register the Swift Mailer instance.
	 *
	 * @return void
	 */
	protected function registerSwiftMailer()
	{
		if ($this->swift instanceof Swift_Mailer) return $this->swift;
		
		$config    = $this->memory->get('email');
		$transport = $this->registerSwiftTransport($config);

		return $this->swift = new Swift_Mailer($transport);
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
		$memory = $this->memory;

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
		$mailer = $this->app['mailer'];
		$mailer->setSwiftMailer($this->registerSwiftMailer());

		return $mailer->send($view, $data, $callback);
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
		
		$with = array(
			'view'     => $view,
			'data'     => $data,
			'callback' => $callback,
		);

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

	/**
	 * Register the Swift Transport instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	protected function registerSwiftTransport($config)
	{
		switch ($config['driver'])
		{
			case 'smtp':
				return $this->registerSmtpTransport($config);

			case 'sendmail':
				return $this->registerSendmailTransport($config);

			case 'mail':
				return $this->registerMailTransport($config);

			default:
				throw new InvalidArgumentException('Invalid mail driver.');
		}
	}

	/**
	 * Register the SMTP Swift Transport instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	protected function registerSmtpTransport($config)
	{
		$transport = SmtpTransport::newInstance($config['host'], $config['port']);

		if (isset($config['encryption']))
		{
			$transport->setEncryption($config['encryption']);
		}

		// Once we have the transport we will check for the presence of a username
		// and password. If we have it we will set the credentials on the Swift
		// transporter instance so that we'll properly authenticate delivery.
		if (isset($config['username']))
		{
			$transport->setUsername($config['username']);
			$transport->setPassword($config['password']);
		}

		return $transport;
	}

	/**
	 * Register the Sendmail Swift Transport instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	protected function registerSendmailTransport($config)
	{
		return SendmailTransport::newInstance($config['sendmail']);
	}

	/**
	 * Register the Mail Swift Transport instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	protected function registerMailTransport($config)
	{
		unset($config);
		return MailTransport::newInstance();
	}
}
