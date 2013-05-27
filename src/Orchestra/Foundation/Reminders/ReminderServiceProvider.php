<?php namespace Orchestra\Foundation\Reminders;

class ReminderServiceProvider extends \Illuminate\Auth\Reminders\ReminderServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the password broker instance.
	 *
	 * @return void
	 */
	protected function registerPasswordBroker()
	{
		$this->app['auth.reminder'] = $this->app->share(function($app)
		{
			// The reminder repository is responsible for storing the user e-mail addresses
			// and password reset tokens. It will be used to verify the tokens are valid
			// for the given e-mail addresses. We will resolve an implementation here.
			$reminders = $app['auth.reminder.repository'];

			$users = $app['auth']->driver()->getProvider();

			$view = $app['config']['auth.reminder.email'];

			// The password broker uses the reminder repository to validate tokens and send
			// reminder e-mails, as well as validating that password reset process as an
			// aggregate service of sorts providing a convenient interface for resets.
			return new PasswordBroker(

				$reminders, $users, $app['redirect'], $app['orchestra.mail'], $app['orchestra.messages'], $view

			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('auth.reminder', 'auth.reminder.repository', 'command.auth.reminders');
	}

}
